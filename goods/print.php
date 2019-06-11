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

			case "print":
				$this->print_proc();
			break;

			// 初期表示
			default:
				$this->default_proc();
			break;
		}

	}

	// 一覧表示
	function default_proc(){

		$this->templ->smarty->assign('page_no',4);
		$this->form_make();
		$this->templ->smarty->display('goods/print.html');
	}

	function form_make(){
		$util = new util();

		$this->templ->smarty->assign('kind_list',$util->kind_list2());
	}

	function print_proc(){


		$_SESSION['kind'] = $this->req->get('kind');

		$this->templ->smarty->assign('kind',$_SESSION['kind']);
		$this->data_get();
	}

	function data_get(){

		$sql = "SELECT COUNT(autono) as count from m_goods";
		$sql .= " WHERE valid_flg=1 AND del_flg=0";
		if ($_SESSION['kind']) {
			if($_SESSION['kind'] !== '12'){
				$sql .= " AND kind =".$this->DB->getQStr($_SESSION['kind']);
			}
		}

		$rs =& $this->DB->ASExecute($sql);
		$count = 0;

		if($rs){
			if(!$rs->EOF){
				$count = $rs->fields('count');
			}
		}
//echo $count."<br>";
		if($count){
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename= m_goods.csv');
			$offset = 0;
			$loop_count = $count / 5000;
			$amari = $count % 5000;
			if($amari){
				$loop_count++;
			}
		}

		$header_flg = true;

		for($i = 0;$i < $loop_count;$i++){
			$sql = "select autono,customer_id,mark,product_number,size,name,del_flg,disp_size,cost,salesunitprice1,salesunitprice2,salesunitprice3,salesunitprice4,salesunitprice5,salesunitprice6,salesunitprice7,salesunitprice8,salesunitprice9,salesunitprice10
 from m_goods";
			$sql .= " WHERE valid_flg=1 AND del_flg=0";
			if ($_SESSION['kind']) {
				if($_SESSION['kind'] !== '12'){
					$sql .= " AND kind =".$this->DB->getQStr($_SESSION['kind']);
				}
			}
			$sql .= " ORDER BY mark,product_number,size ASC";
			$sql .= " LIMIT ".$offset.",5000";
//echo $sql;
			$con =& $this->DB->getCon();
			$con->SetFetchMode(ADODB_FETCH_ASSOC);
			$rs =& $this->DB->ASExecute($sql);

			if($rs){
				while($arr = $rs->FetchRow()){
					$first_flg = true;
					if($header_flg){
						$k = 1;
						foreach($arr as $key2 => $val2){
							if($k == 9){
								echo ","."#START";
							}
							if(!$first_flg){
								echo ",";
							}
							echo mb_convert_encoding($key2, 'SJIS', 'utf-8');
							$header_flg = false;
							$first_flg = false;
							$k++;
						}
						echo ","."#END";
						echo "\r\n";
					}

					$first_flg = true;
					$k = 1;
					foreach($arr as $key2 => $val2){
						if($k == 9){
							echo ","."#START";
						}
						if(!$first_flg){
							echo ",";
						}
						echo mb_convert_encoding(str_replace(",","，",$arr[$key2]), 'SJIS', 'utf-8');
						$first_flg = false;
						$k++;
					}
					echo ","."#END";
					echo "\r\n";
				}
				$rs->close();
			}
			$offset = $offset + 5000;
		}

	}

}

?>