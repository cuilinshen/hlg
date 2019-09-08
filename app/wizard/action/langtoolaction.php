<?php 

/**
 * @version			$Id$
 * @create 			2012-5-1 22:27:14 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight 		Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined('_HEXEC') or die('Restricted access!');

//导入引用文件
HClass::import('app.base.action.AdministratorAction, model.TplModel');

/**
 * 数据库工具类 
 * 
 * @desc
 * 
 * @author 			xjiujiu <xjiujiu@foxmail.com>
 * @package 		app.wizard.action
 * @since			1.0.0
 */
class LangtoolAction extends AdministratorAction
{

    /**
     * @var private $_translate 语言数据操作对象
     */
    private $_translate;

    /**
     * @var private $_mark 语言标识操作对象
     */
    private $_mark;

    /**
     * @var private static $_translateCodeTpl 语言代码模板
     */
    private static $_translateCodeTpl    = '<?php 
/**
 * @version         $Id$
 * @create          2012-5-1 22:27:14 By xjiujiu
 * @description     HongJuZi Framework
 * @copyRight       Copyright (c) 2011-2012 http://www.xjiujiu.com.All right reserved
 */
defined(\'_HEXEC\') or die(\'Restricted access!\');

return %s; 

?>';

    /**
     * @var private $_linkedData 关联对象
     */
    private $_linkedData;

    /**
     * 构造函数 
     * 
     * @access public
     */
    public function __construct()
    {
        $this->_model       = new TplModel();
        $this->_translate   = HClass::quickLoadModel('translate');
        $this->_linkedData  = HClass::quickLoadModel('linkeddata');
    }

    /**
     * 数据库工具入口方法 
     * 
     * @access public
     */
    public function index()
    {
        $this->_assignLangList();
        $this->_assignTplList();

        $this->_render('langtool');
    }

    /**
     * 加载模板列表
     * 
     * @desc
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     */
    protected function _assignTplList()
    {
        $tpl    = HClass::quickLoadModel('tpl');

        HResponse::setAttribute('tplList', $tpl->getAllRows());
    }

    /**
     * 生成语言配置文件 
     * 
     * @access public
     */
    public function generate()
    {
        HVerify::isEmpty(HRequest::getParameter('tpl'), '模板');
        HVerify::isEmpty(HRequest::getParameter('langtype'), '语种');
        $tpl                = HClass::quickLoadModel('tpl');
        $this->_translate   = HClass::quickLoadModel('translate');
        $this->_mark        = HClass::quickLoadModel('mark');
        switch(HRequest::getParameter('tpl')) {
        case 'app':
            $this->_genLangByApp($tpl);
            break;
        default:
            $this->_genLangByTpl($tpl);
            break;
        }
        HResponse::succeed('生成成功！');
    }

    /**
     * 通过应用来生成对应的语言配置文件
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @throws Exception 异常信息
     */
    protected function _genLangByApp($tpl)
    {
        $langList   = $this->_getLangList();
        if(empty($langList)) {
            HResponse::succeed('没有找到需要生成的模板或没有语种可以生成！');
        }
        HClass::import('hongjuzi.filesystem.hdir');
        foreach(HDir::getDirs(ROOT_DIR . 'app') as $appDir) {
            $app        = HDir::getDirName($appDir);
            $tplList    = $tpl->getAllRows('`app` = \'' . $app . '\'');
            $maskIds    = $this->_linkedData->setRelItemModel('tpl', 'mark')->getAllRowsByFields(
                '`id`, `rel_id`, `item_id`',
                HSqlHelper::whereInByListMap('`rel_id`', 'id', $tplList)
            );
            foreach($langList as $lang) {
                $this->_genLangFile(array('app' => $app, 'name' => $app), $lang, $maskIds);
            }
        }
    }

    /**
     * 通过模板来生成对应的语言文件
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @param  HModel $tpl模板对象
     */
    protected function _genLangByTpl($tpl) 
    {
        $tplList    = $this->_getTplList($tpl);
        $langList   = $this->_getLangList();
        if(empty($tplList) || empty($langList)) {
            HResponse::succeed('没有找到需要生成的模板或没有语种可以生成！');
        }
        foreach($tplList as $tplInfo) {
            $itemList   = $this->_linkeddata->getAllRowsByFields('`item_id`, `id`', '`rel_id` = ' . $tplInfo['id']);
            foreach($langList as $langTypeInfo) {
                $this->_genLangFile($tplInfo, $langTypeInfo, $itemList);
            }
        }
    }

    /**
     * 得到模板列表
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @return Array
     */
    protected function _getTplList($tpl)
    {
        if('all' == HRequest::getParameter('tpl')) {
            return $tpl->getAllRows();
        }

        return array($tpl->getRecordById(HRequest::getParameter('tpl')));
    }

    /**
     * 生成语言文件
     * 
     * @author xjiujiu <xjiujiu@foxmail.com>
     * @access protected
     * @param  Array $tplInfo 模板配置信息
     * @param  Array $langTypeInfo 语言类型配置信息
     * @param  Array $maskIds 标识信息
     * @throws Exception  异常
     */
    protected function _genLangFile($tplInfo, $langTypeInfo, $maskIds)
    {
        if(!$tplInfo || !$langTypeInfo || !$maskIds) {
            return;
        }
        //得到翻译列表
        $langList       = $this->_translate->getAllRows(
            '`parent_id` = ' . $langTypeInfo['id']
            . ' AND '
            . HSqlHelper::whereInByListMap('mark_id', 'item_id', $maskIds)
        );
        if(empty($langList)) {
            return;
        }
        $langList       = HArray::turnItemValueAsKey($langList, 'mark_id');
        //得到标识列表
        $markList       = $this->_mark->getAllRows(
            HSqlHelper::whereInByListMap('id', 'item_id', $maskIds)
        );
        //组合成一个合法的PHP 语言数组
        $langMap        = array();
        foreach($markList as $mask) {
            $langMap[$mask['name']]  = $langList[$mask['id']]['content'];
        }
        $langPath       = ROOT_DIR . 'config/i18n/' . $langTypeInfo['identifier']
            . '/' . $tplInfo['app'] . '/' . $tplInfo['name'] . '.php';
        HDir::create(dirname($langPath));

        HFile::write($langPath, sprintf(self::$_translateCodeTpl, var_export($langMap, true)));
    }

}

?>
