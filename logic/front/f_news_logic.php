<?php
/**
 * Created by PhpStorm.
 * User: 2f_info
 * Date: 2019/01/29
 * Time: 11:27
 */
require_once __DIR__ . '/../../logic/common/common_logic.php';
require_once __DIR__ . '/../../model/t_news_model.php';

class f_news_logic
{
    private $common_logic;
    private $model;
    public function __construct()
    {
        $this->common_logic = new common_logic();
        $this->model = new t_news_model();
    }

    public function getNewsBox($limit){
        $newsHTML = $this->getNewsTopHTML($limit);
        $html = '
        	<div class="indexNewsBox">
				<div class="indexNewsMarq">
                    '.$newsHTML.'
				</div>
			</div>

        ';
        return $html;
    }

    public function getNews($limit = 5){

    	//交通情報
    	$sqlAdd = array();
        $sqlAdd['where'] = ' WHERE del_flg = 0 AND public_flg = 0 AND `type` = 1 ';
        $sqlAdd['order'] = ' ORDER BY disp_date DESC ';
        $sqlAdd['whereParam'] = array();

        $news_ko = $this->model->get_news_list(0, 1, $sqlAdd);
        if($news_ko == null || $news_ko == '')$news_ko = array();

        //天気情報
        $sqlAdd = array();
        $sqlAdd['where'] = ' WHERE del_flg = 0 AND public_flg = 0 AND `type` = 2 ';
        $sqlAdd['order'] = ' ORDER BY disp_date DESC ';
        $sqlAdd['whereParam'] = array();

        $news_te = $this->model->get_news_list(0, 1, $sqlAdd);
        if($news_te == null || $news_te == '')$news_te = array();

        //その他
        $sqlAdd = array();
        $sqlAdd['where'] = ' WHERE del_flg = 0 AND public_flg = 0 AND `type` != 1 AND `type` != 2 ';
        $sqlAdd['order'] = ' ORDER BY disp_date DESC ';
        $sqlAdd['whereParam'] = array();

        $news_othrer = $this->model->get_news_list(0, ($limit - 2), $sqlAdd);
        if($news_othrer == null || $news_othrer == '')$news_othrer = array();

        return array_merge($news_ko, $news_te, $news_othrer);
    }

    public function getNewsTopHTML($limit = 5){
        $html = '';
        $data = $this->getNews($limit);
        $pattern = array(
            '/##TYPE_CLASS##/',
            '/##TYPE_NAME##/',
            '/##NEWS_TITLE##/',
            '/##A_TAG##/',
            '/##A_TAG_FINISH##/',
        );


        for ($i=0; $i<count($data); $i++) {
            $a_tag = '';
            $a_tag_finish = '';
            $class = $this->getTypeClass($data[$i]['type']);
            $type_name = $this->getTypeName($data[$i]['type']);

            if ($data[$i]['detail']!='') {
                $a_tag = '<a href="'.$data[$i]['detail'].'" target="_BLANK">';
                $a_tag_finish = '</a>';
            } else if ($data[$i]['img'] != ''){
                $a_tag = '<a href="'.$data[$i]['img'].'" target="_BLANK">';
                $a_tag_finish = '</a>';
            }


            $replacement = array(
                $class,
                $type_name,
                $data[$i]['title'],
                $a_tag,
                $a_tag_finish,
            );

            $html .= preg_replace($pattern, $replacement, $this->getTopTemplate());

        }

        if($html == null || $html == '')$html = '<div class="indexNews">現在表示する記事はありません。</div>';
        return $html;
    }

    private function getTypeClass($type){
        $class_array = array(
	            1=>'indexNewsCateTenki',
	            2=>'indexNewsCateKeizai',
        		3=>'indexNewsCateUnei',
        		4=>'indexNewsCateIppanN',
        		5=>'indexNewsCateKoutsuN',
        		6=>'indexNewsCateKeizaiN',
        		7=>'indexNewsCateSportsN',
        		8=>'indexNewsCateSokuhou',
        		9=>'indexNewsCateSeiji',
        );
        if (isset($class_array[$type])) {
            $class = $class_array[$type];
        } else {
            $class = '';
        }

        return $class;
    }
    private function getTypeName($type) {
        return Constants::getNewsType_string($type);
    }

    /**
     * indexNewsCateTenki
     * indexNewsCateKeizai
     * indexNewsCateKokusai
     */
    private function getTopTemplate(){

        $template = '
        			<div class="indexNews">
        			    ##A_TAG##
                            <div class="##TYPE_CLASS##">
                                ##TYPE_NAME##
                            </div>
                            <div class="indexNewsTxt">
                                ##NEWS_TITLE##
                            </div>
                        ##A_TAG_FINISH##
					</div>

        ';
        return $template;
    }

}