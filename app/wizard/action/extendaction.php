<?php

/**
 * @version			$Id$
 * @create 			2012-4-8 17:52:14 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入模块工具类
HClass::import('hongjuzi.filesystem.HDir');

//导入模块相关类
HClass::import('config.popo.CategoryPopo, config.popo.ModelManagerPopo,
    app.admin.action.AdminAction, model.ModelManagerModel, model.CategoryModel');

/**
 * 生成工具的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.wizard.action
 * @since 			1.0.0
 */
class ExtendAction extends AdminAction
{

    /**
     * @var ExtendModel 分类模块对象 
     */
    private $_extend;

    /**
     * @var String $_popoFileds 模块配置字段信息
     */
    private $_popoFileds;

    /**
     * @var String $_fielTpl 字段的模板
     */
    private $_fieldTpl;

    /**
     * 构造函数 
     * 
     * 初始化类变量 
     * 
     * @access public
     */
    public function __construct() 
    {
        $this->_popoFields  = '';
        $this->_fieldTpl    = '';
        $this->_popo        = new CategoryPopo();
        $this->_popo->modelEnName   = 'extend';
        $this->_model       = new CategoryModel($this->_popo);
        $this->_extend      = new ModelManagerModel($this->_popo);
    }

    /**
     * 编辑动作 
     * 
     * @desc
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function editview()
    {
        HVerify::isRecordId(HRequest::getParameter('id'));
        $record     = $this->_model->getRecordById(HRequest::getParameter('id'));
        if(HVerify::isEmpty($record)) {
            throw new HVerifyException('没有对应的记录！');
        }
        $this->_assignCategoryRootNodes($this->_model);
        
        HResponse::setAttribute('record', $record);
        HResponse::setAttribute('nextAction', 'edit');
        HResponse::setAttribute('popo', $this->_popo);
        HResponse::setAttribute('quickOperations', HObject::GC('QUICK_OPTIONS'));
        //得到当前模块POPO的字段及快捷操作信息 
        $modelPopo  = HObject::loadPopoClass($record['extend_table']);
        HResponse::setAttribute('hasQuickOperations', $modelPopo->get('hasQuickOperations'));
        HResponse::setAttribute('tableList', $this->_extend->getTableList());
        $this->_render('extend/info_view');
    }

    /**
     * 编辑提示动作 
     * 
     * @desc
     * 
     * @access public
     * @exception HVerifyException 验证异常
     */
    public function edit()
    {
        HVerify::isRecordId(HRequest::getParameter('id'));
        $record         = $this->_model->getRecordById(HRequest::getParameter('id'));
        if(empty($record)) {
            throw new HVerifyException('记录不存在，请确认！');
        }
        //自动生成模板相关文件及目录，由于可能会手动改相关的代码文件，这里就不再自动生成
        $this->_autoGenModelFilesAndDirs();
        if(!HRequest::getParameter('on_desktop')) {
            HRequest::setParameter('on_desktop', '否');    
        }
        $this->_uploadFile();
        HRequest::setParameter('extend_table', $this->_extendEnName);
        if(false === $this->_extend->edit(HPopoHelper::getUpdateFieldsAndValues($this->_popo))) {
            throw new HRequestException('更新失败！');
        }
        HResponse::alertAndJump('更新成功！', HResponse::url('admin/extend'));
    }
 
    /**
     * 删除动作 
     * 
     * @desc
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function delete()
    {
        $recordIds  = HRequest::getParameter('id');
        if(!is_array($recordIds)) {
            $recordIds  = array($recordIds);
        }
        foreach($recordIds as $recordId) {
            HVerify::isRecordId($recordId);
            $record     = $this->_extend->getRecordById($recordId);
            if(empty($record)) {
                throw new HVerifyException('ID为：' . $recordId . '信息记录不存在！');
            }
            //删除模块对应的表
            //$this->_extend->dropModelTable($record['en_name']);
            //删除模块相关文件
            $this->_deleteModelFiles($record['en_name']);
            $this->_deleteFiles();
            if(false === $this->_extend->delete($recordId)) {
                throw new HRequestException('ID号为：' . $recordId . ', 删除失败！');
            }
        }
        HResponse::alertAndJump('删除成功！', HResponse::url('admin/extend'));
    }

    /**
     * 删除模块对应的资源项 
     * 
     * @desc
     * 
     * @access protected
     * @exception HRequestException 请求异常 
     */
    protected function _deleteModelFiles($modelEnName)
    {
        foreach(ModelManagerPopo::$deleteResources as $item) {
            try {
                $path   = ROOT_DIR . DS . sprintf($item, $modelEnName);
                HDir::isDir($path) ? HDir::delete($path) : HFile::delete($path);
            } catch(HIOException $ex) {
                if(true == HObject::GC('DEBUG')) {
                    throw new HRequestException($ex->getMessage());
                }
            }
        }
    }

    /**
     * 添加模块视图 
     * 
     * @desc
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function addview()
    {  
        $this->_assignCategoryRootNodes($this->_model);

        HResponse::setAttribute('nextAction', 'add');
        HResponse::setAttribute('popo', $this->_popo);
        HResponse::setAttribute('quickOperations', HObject::GC('QUICK_OPTIONS'));
        HResponse::setAttribute('hasQuickOperations', array());
        HResponse::setAttribute('tableList', $this->_extend->getTableList());

        $this->_render('extend/info_view');
    }

    /**
     * 执行模块的添加 
     * 
     * @desc
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function add()
    {
        if(!HVerify::isEmpty(HRequest::getParameter('parent_id'))) {
            HVerify::isRecordId(HRequest::getParameter('parent_id'));
        }
        $this->_isModelExists();
        //自动生成模板相关文件及目录
        $this->_autoGenModelFilesAndDirs();
        $this->_uploadFile();
        HRequest::setParameter('extend_table', $this->_extendEnName);
        if(false === $this->_model->add(HPopoHelper::getAddFieldsAndValues($this->_popo))) {
            throw new HRequestException('添加失败！');
        }
        HResponse::succeed('添加成功！');
    }

    /**
     * 是否模块已经存在
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @return void
     * @throws HVerifyException 验证异常
     */
    protected function _isModelExists()
    {
        $where  = '`en_name` = \'' . HRequest::getParameter('en_name') . '\'';
        if($this->_model->getRecordByWhere($where)) {
            throw new HVerifyException('模块已经存在，请确认！');
        }
    }

    /**
     * 自动生成模块相关目录及文件 
     * 
     * @desc
     * 
     * @access protected
     * @return void
     */
    protected function _autoGenModelFilesAndDirs()
    {
        $extendTable        = HRequest::getParameter('extend_table');
        $this->_extendZhName = HRequest::getParameter('name');
        $this->_extendEnName = strtolower(substr($extendTable, strpos($extendTable, '_') + 1));
        $fields             = $this->_extend->getTableFields(HRequest::getParameter('extend_table'));
        foreach($fields as $field) {
            $tplType        = 'text';   //默认模板为INPUT TEXT
            $extendsCfg     = explode(HObject::GC('PROTACL_MASK'), $field['Comment']);
            switch(count($extendsCfg)) {
                case 4: break;
                case 3: array_push($extendsCfg, 'text'); break; //默认为text html控件
                case 2: array_push($extendsCfg, '');array_push($extendsCfg, 'text'); break;
                default: throw new HVerifyException($field['Field'] . '字段Comment配置不能满足协议要求，请查看相关说明！'); break;
            }
            $this->_popoFields     .= $this->_parseFieldCfg($field['Field'], $extendsCfg); 
            $this->_fieldTpl       .= $this->_parseFieldTpl($field, $extendsCfg);
        }
        //创建配置文件
        //创建管理模板、代码文件
        $this->_genCodeFiles();
    }

    /**
     * 解析字段的配置信息
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @param  String $field 字段名
     * @param  Array<String> $extendsCfg 字段的注释信息，这里就不再自动生成
     *          成被用作为系统配置的存储字段，格式为：
     *          图片|只支持.jpg, .png格式等|show, asc, image, search
     *          其中“|” 暂设置为协议的分隔符
     * @return String 解析后得到的字段信息 
     */
    protected function _parseFieldCfg($field, $extendsCfg)
    {
        $otherCfg   = '';
        foreach(explode(',', $extendsCfg[2]) as $mask) {
            $mask   = strtolower(trim($mask));
            if(isset(ModelManagerPopo::$fieldMaskCfgMap[trim($mask)])) {
                $otherCfg   .= ModelManagerPopo::$fieldMaskCfgMap[$mask] . ', ';
            }
        }

        return "'{$field}' => array(
            'name' => '{$extendsCfg[0]}',
            'comment' => '{$extendsCfg[1]}',
            {$otherCfg}
        ),";
    }

    /**
     * 解析字段对应的模板信息 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @param  Array<String, String> $field 当前字段的定义集合
     * @param  Array<String> $extendsCfg 字段的注释信息，这里就不再自动生成
     *          成被用作为系统配置的存储字段，格式为：
     *          图片|只支持.jpg, .png格式等|show, asc, image, search
     *          其中“|” 暂设置为协议的分隔符
     * @return String 解析后的字段模板信息
     */
    protected function _parseFieldTpl($field, $extendsCfg)
    {
        $fieldTpl   = ROOT_DIR . DS . HObject::GC('TPL_DIR') . '/admin/fields/' . $field['Field'] . '.tpl';
        if(HFile::isExists($fieldTpl)) {
            return '<?php require_once(HObject::GC(\'TPL_DIR\') . \'/admin/fields/' . $field['Field'] . ".tpl'); ?>\n";
        }
        $tplPath        = ROOT_DIR . '/system/data/html/field/';
        if(!HFile::isExists($tplPath). $extendsCfg[3] . '.hd') {
            $tplPath    .= $extendsCfg[3] . '.hd';
        } else {
            $tplPath    .= 'text.hd';
        }

        return strtr(file_get_contents($tplPath), array(
            '{field}' => $field['Field'],
            '{name}' => $extendsCfg[0],
            '{comment}' => '<?php echo $popo->getFieldComment(\'' . $field['Field'] . "'); ?>\n",
        ));
    }

    /**
     * 生成对应的Popo模块类文件 
     * 
     * @desc
     * 
     * @access protected
     * @return void
     */
    protected function _genPopoFile($file)
    {
        $replaceMap     = array(
            '{now}' => HDatetime::getNow(),
            '{className}' => ucfirst($this->_extendEnName),
            '{modelEnName}' => $this->_extendEnName,
            '{fields}' => $this->_popoFields,
            '{hasQuickOperations}' => var_export(
                $this->_genModelQuickOperationsConfigs(),
                true
            )
        );

        if($false === $this->_makeCodeFile($file, $replaceMap)) {
            throw new HRequestException('生成模块配置文件失败！错误文件：'. $file['src']);
        }
    }

    /**
     * 生成模块快捷操作配置 
     * 
     * @desc
     * 
     * @access protected
     * @return array 过滤后的操作项目
     */
    protected function _genModelQuickOperationsConfigs()
    {
        return array_filter(HRequest::getParameter('hasQO'));
    }

    /**
     * 生成模块相关文件 
     * 
     * @desc
     * 
     * @access protected
     * @return void
     */
    protected function _genCodeFiles()
    {
        foreach(ModelManagerPopo::$tplMap['extend'] as $type => $file) {
            try {
                switch($type) {
                    case 'tpl' : $this->_genHtmlFile($file); break;
                    case 'popo': $this->_genPopoFile($file); break;
                    default: break;
                }
            } catch(HIOException $ex) {
                throw new HRequestException($ex->getMessage());
            }
        }
    }

    /**
     * 生成应用模块对应的HTML代码 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @param  String $file 当前模块文件路径 
     * @return void
     */
    protected function _genHtmlFile($file)
    {
        //创建模板文件 
        $replaceMap     = array(
            '{fieldTemplate}' => $this->_fieldTpl
        );
        if($false == $this->_makeCodeFile($file, $replaceMap)) {
            throw new HRequestException('生成后台管理模板文件失败！错误文件：' . $file['desc']);
        }
    }

    /**
     * 自动生成模块对应类型的代码文件 
     * 
     * 按当前给定的应用，跟类型给模块创建对应的代码文件
     * 
     * @access protected
     * @param  String $file 需要造成的文件
     * @param  Array $replaceMap 模板里需要替换的内容映射
     * @return boolean 是否创建成功 
     */
    protected function _makeCodeFile($file, $replaceMap)
    {
        try {
            if(!isset($file['desc']) || empty($file['desc'])) { //空配置，路过
                return true;
            }
            $desc   = sprintf($file['desc'], $this->_extendEnName);
            HDir::create(HFile::getFileDir($desc));
            $content = HFile::read($file['src']);
            HFile::create(ROOT_DIR . DS . $desc, strtr($content, $replaceMap), true);

            return true;
        } catch(HIOException $ex) {
            return false;
        }
    }

    /**
     * 查找对应表名的详细信息
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function atableinfo()
    {
        try {
            HVerify::isAjax();
            HVerify::isEmpty(HRequest::getParameter('table'));
            $table     = $this->_extend->getTableInfo(HRequest::getParameter('table'));
            if(empty($table)) {
                throw new HVerifyException('对应数据表不存在！');
            }
            $createTableText    = $table['Create Table'];
            $startNeedle        = strrpos($createTableText, 'COMMENT=');
            $comment            = false == $startNeedle ? '' : substr($createTableText, $startNeedle + 9, -1);
            HResponse::json(array('comment' => $comment));
        } catch(HVerifyException $ex) {
            HResponse::json(array('msg' => $ex->getMessage()));
        } catch(HRequestException $ex) {
            HResponse::json(array('msg' => $ex->getMessage()));
        }
    }

    /**
     * 加载当前的表 
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     * @return void
     */
    public function atables()
    {
        try {
            HVerify::isAjax();
            HResponse::json(array('rs' => true, 'tables' => $this->_extend->getTableList()));
        } catch(HVerifyException $ex) {
            HResponse::json(array('rs' => false, 'info' => $ex->getMessage()));
        } catch(HRequestException $ex) {
            HResponse::json(array('rs' => false, 'info' => $ex->getMessage()));
        }
    }

}

?>
