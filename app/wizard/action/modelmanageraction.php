<?php

/**
 * @version			$Id$
 * @create 			2012-4-8 17:52:14 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入模块相关类
HClass::import('hongjuzi.filesystem.hdir, config.popo.ModelManagerPopo, app.admin.action.AdminAction, model.ModelManagerModel');

/**
 * 生成工具的动作类 
 * 
 * 主要处理后台管理主页的相关请求动作 
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.wizard.action
 * @since 			1.0.0
 */
class ModelManagerAction extends AdminAction
{

    /**
     * @var String $_popoFileds 模块配置字段信息
     */
    private $_popoFileds;

    /**
     * @var String $_fielTpl 字段的模板
     */
    private $_fieldTpl;

    /**
     * @var String $_primaryKey 模块表主键
     */
    private $_primaryKey;

    /**
     * 构造函数 
     * 
     * 初始化类变量 
     * 
     * @access public
     */
    public function __construct() 
    {
        parent::__construct();
        $this->_popoFields  = '';
        $this->_primaryKey  = '';
        $this->_fieldTpl    = array(
            'base_fields_tpl' => '',
            'seo_fields_tpl' => '',
            'album_fields_tpl' => '',
            'publish_fields_tpl' => '',
            'manage_fields_tpl' => ''
        );
        $this->_popo        = new ModelManagerPopo();
        $this->_model       = new ModelManagerModel($this->_popo);
    }

    /**
     * 快捷操作面板 
     * 
     * @desc
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function control()
    {
        $this->_render('modelmanager/control_view');
    }

    /**
     * 注册格式化映射
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function _registerFormatMap()
    {
        HResponse::registerFormatMap(
            'type',
            'name',
            HArray::turnItemValueAsKey(
                $this->_getRelationModelList(
                    'category',
                    'type',
                    HResponse::getAttribute('list')
                ), 
                'id'
            )
        );
    }

    /**
     * 编辑动作 
     * 
     * @access public
     * @return void
     * @exception none
     */
    public function editview()
    {
        $this->_editview();
        $this->_assignAllParentList();
        HResponse::setAttribute('popo', $this->_popo);
        //得到当前模块POPO的字段及快捷操作信息 
        HResponse::setAttribute('tableList', $this->_model->getTableList());
        $this->_assignModelPopo();
        $this->_assignApps();
        $this->_assignModelTypeList();
        $this->_render('modelmanager/info');
    }

    /**
     * 加载模块类型列表
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _assignModelTypeList()
    {
        $category   = HClass::quickLoadModel('category');
        HResponse::setAttribute(
            'type_list', 
            $category->getSubCategoryByIdentifier('model-category', false)
        );
    }

    /**
     * 加载模块配置对象
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _assignModelPopo()
    {
        $record     = HResponse::getAttribute('record');
        HResponse::setAttribute('model_popo', HClass::loadPopoClass($record['identifier']));
    }

    /**
     * 加载当前所有的应用
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _assignApps()
    {
        HResponse::setAttribute('apps', HDir::getDirs(ROOT_DIR . 'app'));
    }

    /**
     * 编辑提示动作 
     * 
     * @access public
     */
    public function edit()
    {
        //添加数据记录
        $this->_edit();
        //自动生成模板相关文件及目录
        $this->_autoGenModelFilesAndDirs();
        HResponse::succeed(HTranslate::__('编辑成功'), $this->_getReferenceUrl(2));
    }
 
    /**
     * 删除动作 
     * 
     * @desc
     * 
     * @access public
     */
    public function delete()
    {
        $recordIds  = HRequest::getParameter('id');
        if(!is_array($recordIds)) {
            $recordIds  = array($recordIds);
        }
        foreach($recordIds as $recordId) {
            HVerify::isRecordId($recordId);
            $record     = $this->_model->getRecordById($recordId);
            if(empty($record)) {
                throw new HVerifyException(HTranslate::__('没有这条记录'));
            }
            //删除模块对应的表
            //$this->_model->dropModelTable($record['identifier']);
            //删除模块相关文件
            $this->_deleteModelFiles($record['identifier']);
            $this->_deleteFiles();
            if(false === $this->_model->delete($recordId)) {
                throw new HRequestException(HTranslate::__('删除失败'));
            }
        }
        HResponse::succeed(HTranslate::__('删除成功'), HResponse::url('modelmanager'));
    }

    /**
     * 删除模块对应的资源项 
     * 
     * @access protected
     * @exception HRequestException 请求异常 
     */
    protected function _deleteModelFiles($modelEnName)
    {
        foreach(ModelManagerPopo::$deleteResources['model'] as $item) {
            $path   = ROOT_DIR . sprintf($item, $modelEnName);
            HDir::isDir($path) ? HDir::delete($path) : HFile::delete($path);
        }
        $apps   = HDir::getDirs(ROOT_DIR . 'app');
        foreach(ModelManagerPopo::$deleteResources['app'] as $item) {
            foreach($apps as $appDir) {
                $path   = ROOT_DIR . sprintf($item, HDir::getDirName($appDir), $modelEnName);
                HDir::isDir($path) ? HDir::delete($path) : HFile::delete($path);
            }
        }
    }

    /**
     * 添加模块视图 
     * 
     * @access public
     */
    public function addview()
    {  
        $this->_assignAllParentList();
        HResponse::setAttribute('nextAction', 'add');
        HResponse::setAttribute('popo', $this->_popo);
        HResponse::setAttribute('tableList', $this->_model->getTableList());
        $this->_assignApps();
        $this->_assignModelTypeList();

        $this->_render('modelmanager/info');
    }

    /**
     * 执行模块的添加 
     * 
     * @access public
     */
    public function add()
    {
        $this->_isModelExists();
        $this->_add();
        //自动生成模板相关文件及目录
        $this->_autoGenModelFilesAndDirs();

        HResponse::succeed('添加成功！');
    }

    /**
     * 是否模块已经存在
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @throws HVerifyException 验证异常
     */
    protected function _isModelExists()
    {
        $where  = '`identifier` = \'' . HRequest::getParameter('identifier') . '\'';
        if($this->_model->getRecordByWhere($where)) {
            throw new HVerifyException(HTranslate::__('模块已经存在'));
        }
    }

    /**
     * 自动生成模块相关目录及文件 
     * 
     * @access protected
     */
    protected function _autoGenModelFilesAndDirs()
    {
        if(!HRequest::getParameter('table_name')) {
            return;
        }
        if(!HRequest::getParameter('popo')
            && !HRequest::getParameter('action_files')
            && !HRequest::getParameter('model')
            && !HRequest::getParameter('tpl_files')
        ) {
            return;
        }
        $this->_modelZhName = trim(HRequest::getParameter('name'));
        $this->_modelEnName = strtolower(trim(HRequest::getParameter('identifier')));
        $this->_tableName   = str_replace(
            HObject::GCAttr('DATABASE', 'tablePrefix'),
            '#_', 
            HRequest::getParameter('table_name')
        );
        //解析表字段配置
        $this->_parseTableFields();
        //创建配置文件
        $this->_genPopoFile();
        //生成Model代码文件
        $this->_genModelFile();
        //创建App的Action文件
        $this->_genActionFiles();
        //创建管理模板、代码文件
        $this->_genTplFiles();
        //生成相关的公用资源信息，如：文件存储路径、图片存储路径
        //$this->_genResource();
    }

    /**
     * 解析表字段配置
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _parseTableFields()
    {
        $fields             = $this->_model->getTableFields(HRequest::getParameter('table_name'));
        foreach($fields as $field) {
            $tplType        = 'text';   //默认模板为INPUT TEXT
            $extendsCfg     = explode('|', $field['Comment']);
            switch(count($extendsCfg)) {
                case 4: break;
                case 3: array_push($extendsCfg, 'text'); break; //默认为text html控件
                case 2: array_push($extendsCfg, '');array_push($extendsCfg, 'text'); break;
                default: throw new HVerifyException($field['Field'] . HTranslate::__('字段配置格式不正确')); break;
            }
            $this->_popoFields  .= $this->_parseFieldCfg($field, $extendsCfg); 
            if('PRI' === $field['Key']) {
                $this->_primaryKey  = $field['Field'];
            }
            //id为默认字段
            if('id' === $field['Field']) {
                continue;
            }
            if($field['Field'] == 'lang_id' && '2' == HRequest::getParameter('has_multi_lang')) {
                continue;
            }
            if(in_array($field['Field'], array('sort_num', 'identifier', 'top', 'pass', 'status', 'create_time'))) {
                $this->_fieldTpl['publish_fields_tpl'] .= $this->_parseFieldTpl($field, $extendsCfg); 
                continue;
            }
            if(in_array($field['Field'], array('seo_desc', 'seo_keywords'))) {
                $this->_fieldTpl['seo_fields_tpl']    .= $this->_parseFieldTpl($field, $extendsCfg);
                continue;
            }
            if(in_array($field['Field'], array('recommend', 'total_visits', 'edit_time', 'author'))) {
                $this->_fieldTpl['manage_fields_tpl'] .= $this->_parseFieldTpl($field, $extendsCfg); 
                continue;
            }
            if('album' === $extendsCfg[3]) {
                $this->_fieldTpl['album_fields_tpl']  .= $this->_parseFieldTpl($field, $extendsCfg); 
                continue;
            }
            $this->_fieldTpl['base_fields_tpl']       .= $this->_parseFieldTpl($field, $extendsCfg); 
        }
    }

    /**
     * 解析字段的配置信息
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @param  Array $field 当前的字段集合
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
            if(isset(ModelManagerPopo::$fieldMaskCfgMap[$mask])) {
                $otherCfg   .= ModelManagerPopo::$fieldMaskCfgMap[$mask] . ', ';
            }
        }
        if(false !== strpos('album,file,image', $extendsCfg[3])) {
            $otherCfg   .= ModelManagerPopo::$fieldMaskCfgMap[$extendsCfg[3]];
            HDir::create(ROOT_DIR . HObject::GC('RES_DIR') . DS . $this->_modelEnName);
        }
        //如果不是主键，且没有自增
        if('PRI' !== $field['Key'] || 'auto_increment' !== $field['Extra']) {
            //默认值
            $default    = !HVerify::isEmptyNotZero($field['Default']) && 'CURRENT_TIMESTAMP' !== $field['Default'] ? "'default' => '" . $field['Default'] . "'," : '';
            //不能为空
            $notNull    = 'NO' === $field['Null'] ? "'null' => false," : '';
            //得到当前的类型
            $type       = $this->_getTypeCfg($field['Type']);
        }

        return "'{$field['Field']}' => array(
            'name' => '{$extendsCfg[0]}', {$default}
            'verify' => array({$notNull}{$type}),
            'comment' => '{$extendsCfg[1]}',{$otherCfg}
        ),";
    }

    /**
     * 得到不嬘字段的类型
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     * @param  String $type 字段类型
     * @return String 字段类型
     */
    private function _getTypeCfg($type)
    {
        if(false !== strpos($type, 'int') || strpos($type, 'float') || strpos($type, 'double')) {
            return ' \'numeric\' => true,';
        }
        if(false !== strpos($type, 'char')) {
            return " 'len' => " . substr($type, strpos($type, '(') + 1, -1) . ',';
        }
        if(false !== strpos($type, 'enum')) {
            return " 'options' => " . str_replace('enum', 'array', $type) . ',';
        }

        return '';
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
        if(in_array($field['Field'], array('author', 'tags'))) {
            return '<?php require_once(HResponse::path(\'admin\') . \'/fields/' . $field['Field'] . ".tpl'); ?>\n";
        }
        if(false !== strpos('file,image', $extendsCfg[3])) {
            return '<?php $field = \'' . $field['Field'] . "'; require(HResponse::path('admin') . '/fields/file.tpl'); ?>\n";
        }
        if(!file_exists(HResponse::path('admin') . '/fields/' . $extendsCfg[3] . '.tpl')) {
            throw new HVerifyException($extendsCfg[3] . '表单类型不存在，请您确认！字段：' . $field['Field'] . '。');
        }
        
        return '<?php $field = \'' . $field['Field'] . '\'; require(HResponse::path(\'admin\') . \'/fields/' . $extendsCfg[3] . ".tpl'); ?>\n";
    }

    /**
     * 生成对应的Popo模块类文件 
     * 
     * @desc
     * 
     * @access protected
     */
    protected function _genPopoFile()
    {
        if(!HRequest::getParameter('popo')) { return; }
        $replaceMap     = array(
            '{now}' => date('Y-m-d H:m:s'),
            '{className}' => ucfirst($this->_modelEnName),
            '{modelZhName}' => $this->_modelZhName,
            '{modelEnName}' => $this->_modelEnName,
            '{tableName}' => $this->_tableName,
            '{primaryKey}' => $this->_primaryKey,
            '{parent}' => $this->_getParentName(),
            '{fields}' => $this->_popoFields
        );

        $this->_makeCodeFile(ModelManagerPopo::$filesMap['popo'], $replaceMap);
    }

    /**
     * 得到当前模块所选父类的En文名 
     * 
     * 给popo文件里的parent找到对应的值 
     * 
     * @access protected
     * @return  String 当前的父类名
     */
    protected function _getParentName()
    {
        if('0' == HRequest::getParameter('parent_id')) {
            return $this->_modelEnName; 
        }
        if(HRequest::getParameter('parent_id')) {
            $record     = $this->_model->getRecordById(HRequest::getParameter('parent_id'));
            if($record) {
                return $record['identifier'];
            }
        }

        return null;
    }

    /**
     * 生成模块数据操作层文件
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _genModelFile()
    {
         if(!HRequest::getParameter('model')) { return; }
         $replaceMap     = array(
             '{now}' => date('Y-m-d H:m:s'),
             '{modelZhName}' => $this->_modelZhName,
             '{className}' => ucfirst($this->_modelEnName),
             '{modelEnName}' => $this->_modelEnName
         );
         $this->_makeCodeFile(ModelManagerPopo::$filesMap['model'], $replaceMap);
    }

    /**
     * 生成模块控制层类
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _genActionFiles()
    {
        $replaceMap     = array(
            '{now}' => date('Y-m-d H:m:s'),
            '{modelZhName}' => $this->_modelZhName,
            '{className}' => ucfirst($this->_modelEnName),
            '{modelEnName}' => $this->_modelEnName
        );
        $actionFilesMap  = ModelManagerPopo::$filesMap['action'];
        foreach(HRequest::getParameter('action_files') as $app) {
            $replaceMap['{app}']            = $app;
            $replaceMap['{parentClass}']    = ucfirst($app);
            $fileCfg            = isset($actionFilesMap[$app]) ? $actionFilesMap[$app] : $actionFilesMap['other'];
            $fileCfg['desc']    = str_replace('{app}', $app, $fileCfg['desc']);
            $this->_makeCodeFile($fileCfg, $replaceMap);
        }
    }

    /**
     * 生成应用模板文件
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access private
     */
    private function _genTplFiles()
    {
        $tplFilesCfg    = ModelManagerPopo::$filesMap['tpl'];
        foreach(HRequest::getParameter('tpl_files') as $app) {
            if(!isset($tplFilesCfg[$app])) { continue; }
            //创建模板文件 
            $replaceMap     = array(
                '{modelZhName}' => $this->_modelZhName,
                '{modelEnName}' => $this->_modelEnName,
                '{baseFieldsTpl}' => $this->_fieldTpl['base_fields_tpl'],
                '{seoFieldsTpl}' => $this->_fieldTpl['seo_fields_tpl'],
                '{albumFieldsTpl}' => $this->_fieldTpl['album_fields_tpl'],
                '{publishFieldsTpl}' => $this->_fieldTpl['publish_fields_tpl'],
                '{manageFieldsTpl}' => $this->_fieldTpl['manage_fields_tpl']
            );
            $this->_makeCodeFile($tplFilesCfg[$app], $replaceMap); 
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
     */
    protected function _makeCodeFile($file, $replaceMap)
    {
        if(!isset($file['desc']) || empty($file['desc'])) { //空配置，路过
            return ;
        }
        $desc   = sprintf($file['desc'], $this->_modelEnName);
        HDir::create(HFile::getDir($desc));
        $content = HFile::read($file['src']);
        HFile::create(ROOT_DIR . $desc, strtr($content, $replaceMap), true);
    }

    /**
     * 生成模块相关的资源内容
     * 
     * 如：上传的文件存储目录等
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access public
     */
    public function _genResource()
    {
        foreach(ModelManagerPopo::$filesMap['resource'] as $resource) {
            $path   = ROOT_DIR . sprintf($resource, $this->_modelEnName);
            if(HFile::isExists($path)) {
                continue;
            }
            HDir::isDir($path) ?  HDir::create($path) : HFile::create($path);
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
        HVerify::isAjax();
        HVerify::isEmpty(HRequest::getParameter('table'));
        $table     = $this->_model->getTableInfo(HRequest::getParameter('table'));
        if(empty($table)) {
            throw new HVerifyException(HTranslate::__('没有对应记录'));
        }
        $createTableText    = $table['Create Table'];
        $startNeedle        = strrpos($createTableText, 'COMMENT=');
        $comment            = false === $startNeedle ? '' : substr($createTableText, $startNeedle + 9, -1);
        HResponse::json(array('comment' => $comment));
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
        HVerify::isAjax();
        HResponse::json(array('rs' => true, 'tables' => $this->_model->getTableList()));
    }

}

?>
