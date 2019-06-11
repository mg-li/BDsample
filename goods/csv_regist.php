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
	var $util;
	var $errors = array();
	
	var $inscount;
	var $updcount;
	var $errcount;

	function form_class(){

		$this->templ = new smTemplate();

		$this->req = new reqData();
		$this->DB = new ASDB();

//		$this->mode = $_REQUEST['cop'];
		$this->mode = $this->req->get('cop');
		$this->util = new util;

	}

	function execute(){

		switch($this->mode){

			case 'regist':
				$this->regist_proc();
			break;

			case 'end':
				$this->end_proc();
			break;

			default:
				$this->default_proc();
			break;
		}

	}

	function end_proc(){
		$this->templ->smarty->assign('page_no',4);
		$this->templ->smarty->display('goods/csv_end.html');
	}

	function default_proc(){
		$this->templ->smarty->assign('page_no',2);

		$this->form_make();
		$this->templ->smarty->display('goods/csv_input.html');

	}

	function regist_proc(){
		if($this->req->get('back_x')){
			$this->form_make();
			$this->templ->smarty->assign('page_no',4);
			$this->templ->smarty->display('goods/csv_input.html');
			exit;
		}
//var_dump($_FILES);
		$this->templ->smarty->assign('page_no',4);
		// ファイル内容取得
		if (empty($_FILES)) {
			$this->setError(0, array('error0' => 'ファイルサイズが超えています'));
			$this->templ->error_assign($this);
			$this->form_make();
			$this->templ->smarty->display('goods/csv_input.html');
			exit;
		}

		$files = $_FILES['csvdata'];
		if ($files['size'] == 0 && $files['error'] == 4) {
			$this->setError(0, array('error0' => 'ファイルが選択されていません'));
			$this->templ->error_assign($this);
			$this->form_make();
			$this->templ->smarty->display('goods/csv_input.html');
			exit;
		} else if($files['size'] == 0) {
			$this->setError(0, array('error0' => '空ファイルです'));
			$this->templ->error_assign($this);
			$this->form_make();
			$this->templ->smarty->display('goods/csv_input.html');
			exit;
		}

		// CSVデータ取得(空行,改行を除く)
		$csvdata = @file($files['tmp_name'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//var_dump($csvdata);

		$this->inscount = 0;
		$this->updcount = 0;
		$this->errcount = 0;
		$linage = count($csvdata);

		for($i = 0; $i < $linage; $i++) {
			$line = $csvdata[$i];
//var_dump($line);
//			$line = str_replace(array("\r\n","\n","\r"), '', $line);
			
			if ($i == 0) {
				// 項目名を取得する
				$item = explode(",",$line);
				continue;
			}

			// CSVデータを分割する
			$data = $this->data_get($line, $item);
			if (!$data) {
				$this->setError($i, array('error0' => '項目名数とデータ数が違います'));
				continue;
			}
			// データチェック
			if (!$this->data_check($i, $data)) {
				$this->errcount++;
				continue;
			}
			$this->data_insert($data);
		}
//var_dump($this->inscount);
//var_dump($this->updcount);
//var_dump($this->errcount);
//var_dump($this->errors);
		if (count($this->errors)) {
			$this->templ->error_assign($this);
		}
		$this->templ->smarty->assign('inscount',$this->inscount);
		$this->templ->smarty->assign('updcount',$this->updcount);
		$this->templ->smarty->assign('errcount',$this->errcount);

		$this->templ->smarty->display('goods/csv_end.html');
//		header("Location:/goods/csv_regist.php?cop=end");

	}
	
	function data_insert($data) {
		$today = date('Y/m/d H:i:s');

		$record = null;
		$record['customer_id'] = $data['customer_id'];
		$record['mark'] = $data['mark'];
		$record['product_number'] = $data['product_number'];
//		$record['product_number2'] = $data['product_number2'];
		$record['size'] = $data['size'];
		$record['name'] = mb_convert_encoding($data['name'], 'utf-8', 'SJIS');
//		$record['goods_class_id'] = $data['goods_class_id'];
		if($data['cost']){
			$record['cost'] = $data['cost'];
		}
		else{
			if($data['cost'] == '0'){
				$record['cost'] = 'NULL';
			}
		}
		if($data['salesunitprice1']){
			$record['salesunitprice1'] = $data['salesunitprice1'];
		}
		else{
			$record['salesunitprice1'] = 'NULL';
		}
		if($data['salesunitprice2']){
			$record['salesunitprice2'] = $data['salesunitprice2'];
		}
		else{
			$record['salesunitprice2'] = 'NULL';
		}
		if($data['salesunitprice3']){
			$record['salesunitprice3'] = $data['salesunitprice3'];
		}
		else{
			$record['salesunitprice3'] = 'NULL';
		}
		if($data['salesunitprice4']){
			$record['salesunitprice4'] = $data['salesunitprice4'];
		}
		else{
			$record['salesunitprice4'] = 'NULL';
		}
		if($data['salesunitprice5']){
			$record['salesunitprice5'] = $data['salesunitprice5'];
		}
		else{
			$record['salesunitprice5'] = 'NULL';
		}
		if($data['salesunitprice6']){
			$record['salesunitprice6'] = $data['salesunitprice6'];
		}
		else{
			$record['salesunitprice6'] = 'NULL';
		}
		if($data['salesunitprice7']){
			$record['salesunitprice7'] = $data['salesunitprice7'];
		}
		else{
			$record['salesunitprice7'] = 'NULL';
		}
		if($data['salesunitprice8']){
			$record['salesunitprice8'] = $data['salesunitprice8'];
		}
		else{
			$record['salesunitprice8'] = 'NULL';
		}
		if($data['salesunitprice9']){
			$record['salesunitprice9'] = $data['salesunitprice9'];
		}
		else{
			$record['salesunitprice9'] = 'NULL';
		}
		if($data['salesunitprice10']){
			$record['salesunitprice10'] = $data['salesunitprice10'];
		}
		else{
			$record['salesunitprice10'] = 'NULL';
		}
//		$record['kind'] = $data['kind'];
//		$record['fourstar'] = $data['fourstar'];
		$record['disp_size'] = $data['disp_size'];
//		$record['biko'] = $data['biko'];
//		$record['order_point'] = $data['order_point'];
//		$record['nouhin_flg'] = $data['nouhin_flg'];
//		$record['zaiko_flg'] = $data['zaiko_flg'];
//		$record['haiban_date'] = $data['haiban_date'];
//		$record['related_goods'] = $data['related_goods'];
//		$record['new_product_number'] = $data['new_product_number'];
		$record['del_flg'] = $data['del_flg'];
		$record['update_date'] = $today;

		if($data['autono']){
			$where = "autono=".$this->DB->getQStr($data['autono']);

			$sql = "select * from m_goods where ".$where;
			$rs =& $this->DB->ASExecute($sql);

			$base_mark = "";
			$base_product_number = "";
			$base_size = "";
			if($rs){
				if(!$rs->EOF){
					$base_mark = $rs->fields('mark');
					$base_product_number = $rs->fields('product_number');
					$base_size = $rs->fields('size');
				}
				$rs->Close();
			}

//var_dump($record);
			$ret = $this->DB->getCon()->AutoExecute("m_goods", $record, 'UPDATE',$where);
			if ($ret) {
				$this->updcount++;
			}

			if($base_mark or $base_product_number or $base_size){
				if( $base_mark <> $data['mark'] or $base_product_number <> $data['product_number'] or $base_size <> $data['size']){
					$sql = "update t_azukari set mark='".$data['mark']."',product_number='".$data['product_number']."',size='".$data['size']."' where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
//echo $sql."<br>";
					$this->DB->ASExecute($sql);
					$sql = "update t_zaiko set mark='".$data['mark']."',product_number='".$data['product_number']."',size='".$data['size']."' where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
//echo $sql."<br>";
					$this->DB->ASExecute($sql);
					$sql = "update t_rireki set mark='".$data['mark']."',product_number='".$data['product_number']."',size='".$data['size']."' where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
//echo $sql."<br>";
					$this->DB->ASExecute($sql);
					$sql = "update t_slip_detail set mark='".$data['mark']."',product_number='".$data['product_number']."',size='".$data['size']."' where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
//echo $sql."<br>";
					$this->DB->ASExecute($sql);
					$sql = "update temp_card set mark='".$data['mark']."',product_number='".$data['product_number']."',size='".$data['size']."' where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
//echo $sql."<br>";
					$this->DB->ASExecute($sql);

				}

			}
//exit;


		}
		else{
			$record['create_date'] = $today;
			$ret = $this->DB->getCon()->AutoExecute("m_goods", $record, 'INSERT');
			if ($ret) {
				$this->inscount++;
			}
		}
		if (!$ret) {
			$this->errcount++;
		}
		
		return $ret;
	}
	function data_check($row, $data){
		$error = array();

		if(!$data['customer_id']){
			$error['error1'] = '得意先コードを入力してください';
		}
		elseif( mb_strlen($data['customer_id']) > 20 ){
			$error['error1'] = '得意先コードが長すぎます';
		}
		elseif(!preg_match( $this->util->get_id_pattern() , $data['customer_id'] )){
			$error['error1'] = '得意先コードに不正な文字が含まれています';
		}

		if(!$data['mark']){
			$error['error2'] = 'マークを入力してください';
		}
		elseif( mb_strlen($data['mark']) > 20 ){
			$error['error2'] = 'マークが長すぎます';
		}
		elseif(!preg_match( $this->util->get_id_pattern() , $data['mark'] )){
			$error['error2'] = 'マークに不正な文字が含まれています';
		}
		if(!$data['product_number']){
			$error['error3'] = '品番を入力してください';
		}
		elseif( mb_strlen($data['product_number']) > 20 ){
			$error['error3'] = '品番が長すぎます';
		}
		elseif(!preg_match( $this->util->get_id_pattern() , $data['product_number'] )){
			$error['error3'] = '品番に不正な文字が含まれています';
		}
		if(!$data['size']){
			$error['error4'] = 'サイズを入力してください';
		}
		elseif( mb_strlen($data['size']) > 20 ){
			$error['error4'] = 'サイズが長すぎます';
		}
		elseif(!preg_match( $this->util->get_id_pattern() , $data['size'] )){
			$error['error4'] = 'サイズに不正な文字が含まれています';
		}
		if( mb_strlen($data['name']) > 100 ){
			$error['error5'] = '名称が長すぎます';
		}
		if( mb_strlen($data['disp_size']) > 15 ){
			$error['error51'] = '表示サイズが長すぎます';
		}
		if( mb_strlen($data['biko']) > 100 ){
			$error['error52'] = '備考が長すぎます';
		}
		if( mb_strlen($data['goods_class_id']) > 20 ){
			$error['error6'] = '商品分類IDが長すぎます';
		}
		if( mb_strlen($data['cost']) > 8 ){
			$error['error7'] = '原単価が長すぎます';
		}
		elseif($data['cost'] && !preg_match( '/^[0-9]+$/' , $data['cost'] )){
			$error['error7'] = '原単価が正しくありません';
		}
		if( mb_strlen($data['salesunitprice1']) > 8 ){
			$error['error81'] = '売上単価1が長すぎます';
		}
		elseif($data['salesunitprice1'] && !preg_match( '/^[0-9]+$/' , $data['salesunitprice1'] )){
			$error['error81'] = '売上単価1が正しくありません';
		}
		if( mb_strlen($data['salesunitprice2']) > 8 ){
			$error['error82'] = '売上単価2が長すぎます';
		}
		elseif($data['salesunitprice2'] && !preg_match( '/^[0-9]+$/' , $data['salesunitprice2'] )){
			$error['error82'] = '売上単価2が正しくありません';
		}
		if( mb_strlen($data['salesunitprice3']) > 8 ){
			$error['error83'] = '売上単価3が長すぎます';
		}
		elseif($data['salesunitprice3'] && !preg_match( '/^[0-9]+$/' , $data['salesunitprice3'] )){
			$error['error83'] = '売上単価3が正しくありません';
		}
		if( mb_strlen($data['salesunitprice4']) > 8 ){
			$error['error84'] = '売上単価4が長すぎます';
		}
		elseif($data['salesunitprice4'] && !preg_match( '/^[0-9]+$/' , $data['salesunitprice4'] )){
			$error['error84'] = '売上単価4が正しくありません';
		}
		if( mb_strlen($data['salesunitprice5']) > 8 ){
			$error['error85'] = '売上単価5が長すぎます';
		}
		elseif($data['salesunitprice5'] && !preg_match( '/^[0-9]+$/' , $data['salesunitprice5'] )){
			$error['error85'] = '売上単価5が正しくありません';
		}
		if( mb_strlen($data['salesunitprice6']) > 8 ){
			$error['error86'] = '売上単価6が長すぎます';
		}
		elseif($data['salesunitprice6'] && !preg_match( '/^[0-9]+$/' , $data['salesunitprice6'] )){
			$error['error86'] = '売上単価6が正しくありません';
		}
		if( mb_strlen($data['salesunitprice7']) > 8 ){
			$error['error87'] = '売上単価7が長すぎます';
		}
		elseif($data['salesunitprice7'] && !preg_match( '/^[0-9]+$/' , $data['salesunitprice7'] )){
			$error['error87'] = '売上単価7が正しくありません';
		}
		if( mb_strlen($data['salesunitprice8']) > 8 ){
			$error['error88'] = '売上単価8が長すぎます';
		}
		elseif($data['salesunitprice8'] && !preg_match( '/^[0-9]+$/' , $data['salesunitprice8'] )){
			$error['error88'] = '売上単価8が正しくありません';
		}
		if( mb_strlen($data['salesunitprice9']) > 8 ){
			$error['error89'] = '売上単価9が長すぎます';
		}
		elseif($data['salesunitprice9'] && !preg_match( '/^[0-9]+$/' , $data['salesunitprice9'] )){
			$error['error89'] = '売上単価9が正しくありません';
		}
		if( mb_strlen($data['salesunitprice10']) > 8 ){
			$error['error810'] = '売上単価10が長すぎます';
		}
		elseif($data['salesunitprice10'] && !preg_match( '/^[0-9]+$/' , $data['salesunitprice10'] )){
			$error['error810'] = '売上単価10が正しくありません';
		}
		if($data['kind'] && !preg_match( '/^1|2|3|4|5|6|7|8|9|a|b$/' , $data['kind'] )){
			$error['error9'] = '価格タイプが不正です';
		}
		if( mb_strlen($data['order_point']) > 8 ){
			$error['error91'] = '発注点数量が長すぎます';
		}
		elseif($data['order_point'] && !preg_match( '/^[0-9]+$/' , $data['order_point'] )){
			$error['error91'] = '発注点数量が正しくありません';
		}
		if ($data['autono']) {
			$sql = "select * from m_goods where autono = '".$this->DB->getQStr($data['autono'])."'";
			$rs =& $this->DB->ASExecute($sql);
			if($rs){
				if($rs->EOF){
					$error['error1'] = '更新対象商品が存在しません';
				}
			} else {
				$error['error1'] = '更新対象商品が存在しません';
			}
		}
		$sql = "select * from m_goods";
		$sql .= " where customer_id='".$this->DB->getQStr($data['customer_id'])."'";
		$sql .= " and mark='".$this->DB->getQStr($data['mark'])."'";
		$sql .= " and product_number='".$this->DB->getQStr($data['product_number'])."'";
		$sql .= " and size='".$this->DB->getQStr($data['size'])."'";
		$sql .= " and del_flg = '0'";
		if($data['autono']){
			$sql .= ' and autono != '.$this->DB->getQStr($data['autono']);
		}
		$rs =& $this->DB->ASExecute($sql);
		if($rs){
			if(!$rs->EOF){
				$error['error1'] = 'すでに同じ商品が登録されています';
			}
		}

		if ( count($error) == 0 ) {
			return true;
		} else {
			$this->setError($row, $error);
			return false;
		}
	}

	function data_comp(){

	}

	function check_data_make(){

	}

	function data_get($line, $item){
		// 改行を取り除く
//		$line = str_replace(array("\r\n","\n","\r"), '', $line);
		// 空行は取り除く
		if (empty($line)) {
			return 0;
		}

		$data = explode(",",$line);

		// 項目名と項目数が違う場合は取り除く
		if (count($data) != count($item)) {
			return 0;
		}
		$ret = array();
		foreach($data as $key => $value) {
			$value = str_replace(array(" ","\r\n","\n","\r"), '', $value);
			$ret[$item[$key]] = $value;
		}

		return $ret;

	}

	function form_make(){

	}
    function setError ($row, $error){
    	$this->errors[$row] = $error;
    }
    function getErrors (){
        return $this->errors;
    }
    function hasErrors (){
        return (count($this->errors) > 0);
    }
}

?>