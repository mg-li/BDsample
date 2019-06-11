<?php

include_once("../../apl/top.php");
include_once("../../apl/admin.php");

$form_class = new form_class();
$form_class->execute();
exit;

class form_class{
	
	var $req;
	var $mode;
	var $templ;
	var $DB;
	
	function form_class(){

		$this->templ = new smTemplate();

		$this->req = new reqData();
		
		$this->DB = new ASDB();

		$this->mode = $_REQUEST['cop'];

	}
	
	function execute(){

		switch($this->mode){

			case "search":
				$this->search_proc();
			break;

			case "del":
				$this->del_proc();
			break;

			// 一覧表示
			default:
				$this->default_proc();
			break;
		}

	}

	// 一覧表示
	function default_proc(){
		
		$this->templ->smarty->assign('page_no',4);
		$this->form_make();
		$this->templ->smarty->display('goods/search.html');
	}
	
	function form_make(){
		$util = new util();
		
		$this->templ->smarty->assign('goods_class_id_list',$util->goods_class_id_list($this->DB));
	}
	
	function del_proc(){
		if($this->req->get('at')){
			$record['del_flg'] = 1;
	
			if($this->req->get('at')){
				$where = "autono=".$this->DB->getQStr($this->req->get('at'));
	
				$ret = $this->DB->getCon()->AutoExecute("m_goods", $record, 'UPDATE',$where);
			}
		}
		header("Location:./search.php?cop=search");
	}
	
	function search_proc(){

		if($this->req->get('search')){
			$_SESSION['customer_id'] = $this->req->get('customer_id');
			$_SESSION['mark'] = $this->req->get('mark');
			$_SESSION['product_number'] = $this->req->get('product_number');
			$_SESSION['size'] = $this->req->get('size');
			$_SESSION['name'] = $this->req->get('name');
			$_SESSION['goods_class_id'] = $this->req->get('goods_class_id');
			
		}

		$this->templ->smarty->assign('customer_id',$_SESSION['customer_id']);
		$this->templ->smarty->assign('mark',$_SESSION['mark']);
		$this->templ->smarty->assign('product_number',$_SESSION['product_number']);
		$this->templ->smarty->assign('size',$_SESSION['size']);
		$this->templ->smarty->assign('name',$_SESSION['name']);
		$this->templ->smarty->assign('goods_class_id',$_SESSION['goods_class_id']);
		$this->data_get();
	}
	
	function data_get(){

		$where = " where valid_flg=1 and del_flg=0";

		//
		if ( $_SESSION['customer_id'] ) {
		
			$val = $this->DB->getQStr( $_SESSION['customer_id'] ) ;
			$where .= " AND customer_id LIKE '%".$val."%'";

		}

		//
		if ( $_SESSION['mark'] ) {
		
			$val = $this->DB->getQStr( $_SESSION['mark'] ) ;
			$where .= " AND mark LIKE '".$val."%'";

		}

		//
		if ( $_SESSION['product_number'] ) {
		
			$val = $this->DB->getQStr( $_SESSION['product_number'] ) ;
			$where .= " AND product_number LIKE '".$val."%'";

		}

		//
		if ( $_SESSION['size'] ) {
		
			$val = $this->DB->getQStr( $_SESSION['size'] ) ;
			$where .= " AND size LIKE '".$val."%'";

		}

		//
		if ( $_SESSION['name'] ) {
		
			$val = $this->DB->getQStr( $_SESSION['name'] ) ;
			$where .= " AND name LIKE '%".$val."%'";

		}

		if ( $_SESSION['goods_class_id'] ) {
		
			$val = $this->DB->getQStr( $_SESSION['goods_class_id'] ) ;
			$where .= " AND goods_class_id = '".$val."'";

		}

		//公告情報を取得
		$order_sql = " order by customer_id,mark,product_number,size ";

		$sql = "select count(autono) as count from m_goods";
		$sql .= $where;

		$count = 0;

		$rs =& $this->DB->ASExecute($sql);

		if($rs){
			if(!$rs->EOF){
				$count = $rs->fields('count');
			}
		}

//echo $count."<br>";
		$limit_sql = "";
		if($count){

			//ページング処理
			$params = array('perPage' => LIST_MAX,
							'urlVar' => PAGE_ID_NAME,
							'totalItems' => $count,
							'path' => '/',
							'fileName' => "/goods/search.php?cop=search&".PAGE_ID_NAME."=%d",
							'append' => false);

			$pager = new Pager_Sliding($params);
			$links = $pager->getLinks();
			$page = $pager->getCurrentPageID();
			$pagenum = $pager->numPages();
			$offset = ($page - 1) * LIST_MAX;
			$from = $offset + 1;
			if($offset + LIST_MAX > $count){
				$to = $count;
			}
			else{
				$to = $offset + LIST_MAX;
			}
			$page_array = array("count"=>$count,
	               "from"=>$from,
	               "to"=>$to,
	               "page"=>$page,
	               "pagenum"=>$pagenum);
			$this->templ->smarty->assign('page_array', $page_array);
			$this->templ->smarty->assign('link_text', $links['all']);

			//LIMIT文生成
			$limit_sql = " LIMIT ".LIST_MAX." OFFSET ".$offset;

		}

		$sql = "select * from m_goods";
		$sql .= $where ;
		$sql .= $order_sql;
		$sql .= $limit_sql;

		$rs =& $this->DB->ASExecute($sql);
//echo $sql."<br>";
		$i = 0;
		$find_flg = 0;

		if($rs){
			$util = new util();
			while(!$rs->EOF){
				$count = $rs->fields('count');

				$find_flg = 1;
				$search_list[$i]['autono'] = $rs->fields('autono');
				$search_list[$i]['customer_id'] = $rs->fields('customer_id');
				$search_list[$i]['mark'] = $rs->fields('mark');
				$search_list[$i]['product_number'] = $rs->fields('product_number');
				$search_list[$i]['size'] = $rs->fields('size');
				$search_list[$i]['name'] = $rs->fields('name');
				$search_list[$i]['goods_class_id'] = $rs->fields('goods_class_id');
				$search_list[$i]['cost'] = $rs->fields('cost');
				$search_list[$i]['salesunitprice'] = $rs->fields('salesunitprice');
				$search_list[$i]['kind'] = $rs->fields('kind');
				$search_list[$i]['kind_value'] = $util->kind_list(1,$rs->fields('kind'));
				$search_list[$i]['goods_class_id_value'] = $util->goods_class_id_list($this->DB,1,$rs->fields('goods_class_id'));

				$search_list[$i]['valid_flg'] = $rs->fields('valid_flg');
				if($rs->fields('valid_flg') == 1){
					$search_list[$i]['valid_flg_value'] = "有効";
				}
				else{
					$search_list[$i]['valid_flg_value'] = "無効";					
				}
				$i++;
				$rs->MoveNext();
			}

		}

		$this->templ->smarty->assign('search_flg',1);
		$this->templ->smarty->assign('data_list',$search_list);
		$this->templ->smarty->assign('find_flg',$find_flg);
		$this->templ->smarty->assign('page_no',4);
		$this->form_make();

		// テンプレート
		$this->templ->smarty->display('goods/search.html');
	
	}

}

?>