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

	function form_class(){

		$this->templ = new smTemplate();

		$this->req = new reqData();
		$this->DB = new ASDB();

		$this->mode = $_REQUEST['cop'];
		$this->util = new util;

	}

	function execute(){

		switch($this->mode){

			case 'check':
				$this->check_proc();
			break;

			case 'regist':
				$this->regist_proc();
			break;

			case 'end':
				$this->end_proc();
			break;

			case 'del':
				$this->del_proc();
			break;

			default:
				$this->default_proc();
			break;
		}

	}

	function end_proc(){
		$this->templ->smarty->assign('page_no',4);
		$this->templ->smarty->display('goods/end.html');
	}

	function del_proc(){

		if($this->req->get('at')){

			$sql = "delete from m_goods where autono=".$this->DB->getQStr($this->req->get('at'));
			$rs =& $this->DB->ASExecute($sql);

			header("Location:./search.php?cop=search");
		}
	}

	function default_proc(){

		$this->templ->smarty->assign('page_no',2);
		if($this->req->get('at')){
			$this->data_get($this->req->get('at'));
		}
		else{
			$this->templ->smarty->assign('zaiko_flg',1);
			$this->templ->smarty->assign('nouhin_flg',1);
		}
		$this->form_make();
		$this->templ->smarty->display('goods/input.html');

	}

	function check_proc(){

		$this->templ->smarty->assign('at',$this->req->get('at'));

		if($this->req->get('clear_x')){
			$this->templ->smarty->assign('page_no',4);
			$this->form_make();
			$this->templ->smarty->display('goods/input.html');
			exit;
		}

		$this->templ->smarty->assign('customer_id',$this->req->get('customer_id') );
		$this->templ->smarty->assign('mark',$this->req->get('mark') );
		$this->templ->smarty->assign('product_number',$this->req->get('product_number') );
		$this->templ->smarty->assign('product_number2',$this->req->get('product_number2') );
		$this->templ->smarty->assign('size',$this->req->get('size') );
		$this->templ->smarty->assign('fourstar',$this->req->get('fourstar') );
		$this->templ->smarty->assign('name',$this->req->get('name') );
		$this->templ->smarty->assign('goods_class_id',$this->req->get('goods_class_id') );
		$this->templ->smarty->assign('cost',$this->req->get('cost') );
		$this->templ->smarty->assign('salesunitprice1',$this->req->get('salesunitprice1') );
		$this->templ->smarty->assign('salesunitprice2',$this->req->get('salesunitprice2') );
		$this->templ->smarty->assign('salesunitprice3',$this->req->get('salesunitprice3') );
		$this->templ->smarty->assign('salesunitprice4',$this->req->get('salesunitprice4') );
		$this->templ->smarty->assign('salesunitprice5',$this->req->get('salesunitprice5') );
		$this->templ->smarty->assign('salesunitprice6',$this->req->get('salesunitprice6') );
		$this->templ->smarty->assign('salesunitprice7',$this->req->get('salesunitprice7') );
		$this->templ->smarty->assign('salesunitprice8',$this->req->get('salesunitprice8') );
		$this->templ->smarty->assign('salesunitprice9',$this->req->get('salesunitprice9') );
		$this->templ->smarty->assign('salesunitprice10',$this->req->get('salesunitprice10') );
		$this->templ->smarty->assign('kind',$this->req->get('kind') );
		$this->templ->smarty->assign('disp_size',$this->req->get('disp_size') );
		$this->templ->smarty->assign('biko',$this->req->get('biko') );
		$this->templ->smarty->assign('order_point',$this->req->get('order_point') );
		$this->templ->smarty->assign('zaiko_flg',$this->req->get('zaiko_flg') );
		$this->templ->smarty->assign('nouhin_flg',$this->req->get('nouhin_flg') );
		$this->templ->smarty->assign('haiban_date',$this->req->get('haiban_date') );
		$this->templ->smarty->assign('related_goods',$this->req->get('related_goods') );
		$this->templ->smarty->assign('new_product_number',$this->req->get('new_product_number') );
		$this->templ->smarty->assign('headoffice3f_flg',$this->req->get('headoffice3f_flg') );

		$this->data_comp();
		$this->data_check();

		$this->templ->smarty->assign('page_no',4);
		if($this->req->hasErrors()){
			$this->templ->error_assign($this->req);
			$this->form_make();
			$this->templ->smarty->display('goods/input.html');
			exit;
		}

		$this->check_data_make();
		$this->templ->smarty->display('goods/input_check.html');

	}

	function regist_proc(){

		$this->templ->smarty->assign('at',$this->req->get('at'));
		$this->templ->smarty->assign('customer_id',$this->req->get('customer_id') );
		$this->templ->smarty->assign('mark',$this->req->get('mark') );
		$this->templ->smarty->assign('product_number',$this->req->get('product_number') );
		$this->templ->smarty->assign('product_number2',$this->req->get('product_number2') );
		$this->templ->smarty->assign('size',$this->req->get('size') );
		$this->templ->smarty->assign('fourstar',$this->req->get('fourstar') );
		$this->templ->smarty->assign('name',$this->req->get('name') );
		$this->templ->smarty->assign('goods_class_id',$this->req->get('goods_class_id') );
		$this->templ->smarty->assign('cost',$this->req->get('cost') );
		$this->templ->smarty->assign('salesunitprice1',$this->req->get('salesunitprice1') );
		$this->templ->smarty->assign('salesunitprice2',$this->req->get('salesunitprice2') );
		$this->templ->smarty->assign('salesunitprice3',$this->req->get('salesunitprice3') );
		$this->templ->smarty->assign('salesunitprice4',$this->req->get('salesunitprice4') );
		$this->templ->smarty->assign('salesunitprice5',$this->req->get('salesunitprice5') );
		$this->templ->smarty->assign('salesunitprice6',$this->req->get('salesunitprice6') );
		$this->templ->smarty->assign('salesunitprice7',$this->req->get('salesunitprice7') );
		$this->templ->smarty->assign('salesunitprice8',$this->req->get('salesunitprice8') );
		$this->templ->smarty->assign('salesunitprice9',$this->req->get('salesunitprice9') );
		$this->templ->smarty->assign('salesunitprice10',$this->req->get('salesunitprice10') );
		$this->templ->smarty->assign('kind',$this->req->get('kind') );
		$this->templ->smarty->assign('disp_size',$this->req->get('disp_size') );
		$this->templ->smarty->assign('biko',$this->req->get('biko') );
		$this->templ->smarty->assign('order_point',$this->req->get('order_point') );
		$this->templ->smarty->assign('zaiko_flg',$this->req->get('zaiko_flg') );
		$this->templ->smarty->assign('nouhin_flg',$this->req->get('nouhin_flg') );
		$this->templ->smarty->assign('haiban_date',$this->req->get('haiban_date') );
		$this->templ->smarty->assign('related_goods',$this->req->get('related_goods') );
		$this->templ->smarty->assign('new_product_number',$this->req->get('new_product_number') );

		if($this->req->get('back_x')){
			$this->form_make();
			$this->templ->smarty->assign('page_no',4);
			$this->templ->smarty->display('goods/input.html');
			exit;
		}

		$this->data_check();
		$this->templ->smarty->assign('page_no',4);
		if($this->req->hasErrors()){
			$this->templ->error_assign($this->req);
			$this->form_make();
			$this->templ->smarty->display('goods/input.html');
			exit;
		}

		$record = null;
		$record['customer_id'] = $this->req->get('customer_id');
		$record['mark'] = $this->req->get('mark');
		$record['product_number'] = $this->req->get('product_number');
		$record['product_number2'] = $this->req->get('product_number2');
		$record['size'] = $this->req->get('size');
		$record['name'] = $this->req->get('name');
		$record['goods_class_id'] = $this->req->get('goods_class_id');
		/* upd 20090924 金額nullの場合は、DBにもnullで入るよう変更
		$record['cost'] = $this->req->get('cost');
		$record['salesunitprice1'] = $this->req->get('salesunitprice1');
		$record['salesunitprice2'] = $this->req->get('salesunitprice2');
		$record['salesunitprice3'] = $this->req->get('salesunitprice3');
		$record['salesunitprice4'] = $this->req->get('salesunitprice4');
		$record['salesunitprice5'] = $this->req->get('salesunitprice5');
		$record['salesunitprice6'] = $this->req->get('salesunitprice6');
		$record['salesunitprice7'] = $this->req->get('salesunitprice7');
		$record['salesunitprice8'] = $this->req->get('salesunitprice8');
		$record['salesunitprice9'] = $this->req->get('salesunitprice9');
		$record['salesunitprice10'] = $this->req->get('salesunitprice10');*/
		if($this->req->get('cost')){
			$record['cost'] = $this->req->get('cost');
		}
		else{
			if($this->req->get('cost') == '0'){
				$record['cost'] = 0;
			}
			else{
				$record['cost'] = 'NULL';
			}
		}
		if($this->req->get('salesunitprice1')){
			$record['salesunitprice1'] = $this->req->get('salesunitprice1');
		}
		else{
			if($this->req->get('salesunitprice1') == '0'){
				$record['salesunitprice1'] = 0;
			}
			else{
				$record['salesunitprice1'] = 'NULL';
			}
		}
		if($this->req->get('salesunitprice2')){
			$record['salesunitprice2'] = $this->req->get('salesunitprice2');
		}
		else{
			if($this->req->get('salesunitprice2') == '0'){
				$record['salesunitprice2'] = 0;
			}
			else{
				$record['salesunitprice2'] = 'NULL';
			}
		}
		if($this->req->get('salesunitprice3')){
			$record['salesunitprice3'] = $this->req->get('salesunitprice3');
		}
		else{
			if($this->req->get('salesunitprice3') == '0'){
				$record['salesunitprice3'] = 0;
			}
			else{
				$record['salesunitprice3'] = 'NULL';
			}
		}
		if($this->req->get('salesunitprice4')){
			$record['salesunitprice4'] = $this->req->get('salesunitprice4');
		}
		else{
			if($this->req->get('salesunitprice4') == '0'){
				$record['salesunitprice4'] = 0;
			}
			else{
				$record['salesunitprice4'] = 'NULL';
			}
		}
		if($this->req->get('salesunitprice5')){
			$record['salesunitprice5'] = $this->req->get('salesunitprice5');
		}
		else{
			if($this->req->get('salesunitprice5') == '0'){
				$record['salesunitprice5'] = 0;
			}
			else{
				$record['salesunitprice5'] = 'NULL';
			}
		}
		if($this->req->get('salesunitprice6')){
			$record['salesunitprice6'] = $this->req->get('salesunitprice6');
		}
		else{
			if($this->req->get('salesunitprice6') == '0'){
				$record['salesunitprice6'] = 0;
			}
			else{
				$record['salesunitprice6'] = 'NULL';
			}
		}
		if($this->req->get('salesunitprice7')){
			$record['salesunitprice7'] = $this->req->get('salesunitprice7');
		}
		else{
			if($this->req->get('salesunitprice7') == '0'){
				$record['salesunitprice7'] = 0;
			}
			else{
				$record['salesunitprice7'] = 'NULL';
			}
		}
		if($this->req->get('salesunitprice8')){
			$record['salesunitprice8'] = $this->req->get('salesunitprice8');
		}
		else{
			if($this->req->get('salesunitprice8') == '0'){
				$record['salesunitprice8'] = 0;
			}
			else{
				$record['salesunitprice8'] = 'NULL';
			}
		}
		if($this->req->get('salesunitprice9')){
			$record['salesunitprice9'] = $this->req->get('salesunitprice9');
		}
		else{
			if($this->req->get('salesunitprice9') == '0'){
				$record['salesunitprice9'] = 0;
			}
			else{
				$record['salesunitprice9'] = 'NULL';
			}
		}
		if($this->req->get('salesunitprice10')){
			$record['salesunitprice10'] = $this->req->get('salesunitprice10');
		}
		else{
			if($this->req->get('salesunitprice10') == '0'){
				$record['salesunitprice10'] = 0;
			}
			else{
				$record['salesunitprice10'] = 'NULL';
			}
		}
		$record['kind'] = $this->req->get('kind');
		$record['fourstar'] = $this->req->get('fourstar');
		$record['disp_size'] = $this->req->get('disp_size');
		$record['biko'] = $this->req->get('biko');
		$record['order_point'] = $this->req->get('order_point');
		$record['nouhin_flg'] = $this->req->get('nouhin_flg');
		$record['zaiko_flg'] = $this->req->get('zaiko_flg');
		$record['haiban_date'] = $this->req->get('haiban_date');
		$record['related_goods'] = $this->req->get('related_goods');
		$record['new_product_number'] = $this->req->get('new_product_number');

		if($this->req->get('at')){
			$where = "autono=".$this->DB->getQStr($this->req->get('at'));

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

			$ret = $this->DB->getCon()->AutoExecute("m_goods", $record, 'UPDATE',$where);

			if($base_mark or $base_product_number or $base_size){
				if( $base_mark <> $this->req->get('mark') or $base_product_number <> $this->req->get('product_number') or $base_size <> $this->req->get('size')){
					$sql = "update t_azukari set mark='".$this->req->get('mark')."',product_number='".$this->req->get('product_number')."',size='".$this->req->get('size')."' where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
//echo $sql."<br>";
					$this->DB->ASExecute($sql);
					//$sql = "update t_zaiko set mark='".$this->req->get('mark')."',product_number='".$this->req->get('product_number')."',size='".$this->req->get('size')."' where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
					$sql = "update t_zaiko set mark='".$this->req->get('mark')."',product_number='".$this->req->get('product_number')."',size='".$this->req->get('size')."',headoffice3f_flg=".$this->req->get('headoffice3f_flg')." where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
//echo $sql."<br>";
					$this->DB->ASExecute($sql);
					$sql = "update t_rireki set mark='".$this->req->get('mark')."',product_number='".$this->req->get('product_number')."',size='".$this->req->get('size')."' where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
//echo $sql."<br>";
					$this->DB->ASExecute($sql);
					$sql = "update t_slip_detail set mark='".$this->req->get('mark')."',product_number='".$this->req->get('product_number')."',size='".$this->req->get('size')."' where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
//echo $sql."<br>";
					$this->DB->ASExecute($sql);
					$sql = "update temp_card set mark='".$this->req->get('mark')."',product_number='".$this->req->get('product_number')."',size='".$this->req->get('size')."' where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
//echo $sql."<br>";
					$this->DB->ASExecute($sql);

				} else {
                    // 在庫場所のみの変更
					$sql = "update t_zaiko set headoffice3f_flg=".$this->req->get('headoffice3f_flg')." where mark='".$base_mark."' and product_number='".$base_product_number."' and size='".$base_size."'";
					$this->DB->ASExecute($sql);
                }

			}
//exit;


		}
		else{

			$ret = $this->DB->getCon()->AutoExecute("m_goods", $record, 'INSERT');

			$sql = sprintf("insert into t_zaiko (customer_id,mark,product_number,size,zaiko,headoffice3f_flg,zaikobasyo_id) " .
        			       " values ('%s', '%s', '%s', '%s', 0, '%s', 1)",
		        		   $this->req->get('customer_id'),
				           $this->req->get('mark'),
        				   $this->req->get('product_number'),
		        		   $this->req->get('size'),
				           $this->req->get('headoffice3f_flg')
            			  );
			$this->DB->ASExecute($sql);
		}

		header("Location:/goods/regist.php?cop=end");

	}

	function data_check(){

		if(!$this->req->get('customer_id')){
			$this->req->setError('error1','得意先コードを入力してください');
		}
		elseif( mb_strlen($this->req->get('customer_id')) > 20 ){
			$this->req->setError('error1','得意先コードが長すぎます');
		}
		elseif(!preg_match( $this->util->get_id_pattern() , $this->req->get('customer_id') )){
			$this->req->setError('error1','得意先コードに不正な文字が含まれています');
		}

		if(!$this->req->get('mark')){
			$this->req->setError('error2','マークを入力してください');
		}
		elseif( mb_strlen($this->req->get('mark')) > 20 ){
			$this->req->setError('error2','マークが長すぎます');
		}
		elseif(!preg_match( $this->util->get_id_pattern() , $this->req->get('mark') )){
			$this->req->setError('error2','マークに不正な文字が含まれています');
		}
		if(!$this->req->get('product_number')){
			$this->req->setError('error3','品番を入力してください');
		}
		elseif( mb_strlen($this->req->get('product_number')) > 20 ){
			$this->req->setError('error3','品番が長すぎます');
		}
		elseif(!preg_match( $this->util->get_id_pattern() , $this->req->get('product_number') )){
			$this->req->setError('error3','品番に不正な文字が含まれています');
		}
		if(!$this->req->get('size')){
			$this->req->setError('error4','サイズを入力してください');
		}
		elseif( mb_strlen($this->req->get('size')) > 20 ){
			$this->req->setError('error4','サイズが長すぎます');
		}
		elseif(!preg_match( $this->util->get_id_pattern() , $this->req->get('size') )){
			$this->req->setError('error4','サイズに不正な文字が含まれています');
		}
//		elseif(!preg_match( '/^[0-9]+$/' , $this->req->get('size') )){
//			$this->req->setError('error4','サイズが正しくありません');
//		}
//		if(!$this->req->get('name')){
//			$this->req->setError('error5','名称を入力してください');
//		}
		if( mb_strlen($this->req->get('name')) > 100 ){
			$this->req->setError('error5','名称が長すぎます');
		}
		if( mb_strlen($this->req->get('disp_size')) > 15 ){
			$this->req->setError('error51','表示サイズが長すぎます');
		}
		if( mb_strlen($this->req->get('biko')) > 100 ){
			$this->req->setError('error52','備考が長すぎます');
		}
//		if(!$this->req->get('goods_class_id')){
//			$this->req->setError('error6','商品分類IDを選択してください');
//		}
		if( mb_strlen($this->req->get('goods_class_id')) > 20 ){
			$this->req->setError('error6','商品分類IDが長すぎます');
		}
///		if(!$this->req->get('cost')){
//			$this->req->setError('error7','原単価を入力してください');
//		}
		if( mb_strlen($this->req->get('cost')) > 8 ){
			$this->req->setError('error7','原単価が長すぎます');
		}
		elseif($this->req->get('cost') && !preg_match( '/^[0-9]+$/' , $this->req->get('cost') )){
			$this->req->setError('error7','原単価が正しくありません');
		}
//		if(!$this->req->get('salesunitprice')){
//			$this->req->setError('error8','売上単価を入力してください');
//		}
		if( mb_strlen($this->req->get('salesunitprice1')) > 8 ){
			$this->req->setError('error81','売上単価1が長すぎます');
		}
		elseif($this->req->get('salesunitprice1') && !preg_match( '/^[0-9]+$/' , $this->req->get('salesunitprice1') )){
			$this->req->setError('error81','売上単価1が正しくありません');
		}
		if( mb_strlen($this->req->get('salesunitprice2')) > 8 ){
			$this->req->setError('error82','売上単価2が長すぎます');
		}
		elseif($this->req->get('salesunitprice2') && !preg_match( '/^[0-9]+$/' , $this->req->get('salesunitprice2') )){
			$this->req->setError('error82','売上単価2が正しくありません');
		}
		if( mb_strlen($this->req->get('salesunitprice3')) > 8 ){
			$this->req->setError('error83','売上単価3が長すぎます');
		}
		elseif($this->req->get('salesunitprice3') && !preg_match( '/^[0-9]+$/' , $this->req->get('salesunitprice3') )){
			$this->req->setError('error83','売上単価3が正しくありません');
		}
		if( mb_strlen($this->req->get('salesunitprice4')) > 8 ){
			$this->req->setError('error84','売上単価4が長すぎます');
		}
		elseif($this->req->get('salesunitprice4') && !preg_match( '/^[0-9]+$/' , $this->req->get('salesunitprice4') )){
			$this->req->setError('error84','売上単価4が正しくありません');
		}
		if( mb_strlen($this->req->get('salesunitprice5')) > 8 ){
			$this->req->setError('error85','売上単価5が長すぎます');
		}
		elseif($this->req->get('salesunitprice5') && !preg_match( '/^[0-9]+$/' , $this->req->get('salesunitprice5') )){
			$this->req->setError('error85','売上単価5が正しくありません');
		}
		if( mb_strlen($this->req->get('salesunitprice6')) > 8 ){
			$this->req->setError('error86','売上単価6が長すぎます');
		}
		elseif($this->req->get('salesunitprice6') && !preg_match( '/^[0-9]+$/' , $this->req->get('salesunitprice6') )){
			$this->req->setError('error86','売上単価6が正しくありません');
		}
		if( mb_strlen($this->req->get('salesunitprice7')) > 8 ){
			$this->req->setError('error87','売上単価7が長すぎます');
		}
		elseif($this->req->get('salesunitprice7') && !preg_match( '/^[0-9]+$/' , $this->req->get('salesunitprice7') )){
			$this->req->setError('error87','売上単価7が正しくありません');
		}
		if( mb_strlen($this->req->get('salesunitprice8')) > 8 ){
			$this->req->setError('error88','売上単価8が長すぎます');
		}
		elseif($this->req->get('salesunitprice8') && !preg_match( '/^[0-9]+$/' , $this->req->get('salesunitprice8') )){
			$this->req->setError('error88','売上単価8が正しくありません');
		}
		if( mb_strlen($this->req->get('salesunitprice9')) > 8 ){
			$this->req->setError('error89','売上単価9が長すぎます');
		}
		elseif($this->req->get('salesunitprice9') && !preg_match( '/^[0-9]+$/' , $this->req->get('salesunitprice9') )){
			$this->req->setError('error89','売上単価9が正しくありません');
		}
		if( mb_strlen($this->req->get('salesunitprice10')) > 8 ){
			$this->req->setError('error810','売上単価10が長すぎます');
		}
		elseif($this->req->get('salesunitprice10') && !preg_match( '/^[0-9]+$/' , $this->req->get('salesunitprice10') )){
			$this->req->setError('error810','売上単価10が正しくありません');
		}
//		if(!$this->req->get('kind')){
//			$this->req->setError('error9','価格タイプを選択してください');
//		}
		if($this->req->get('kind') && !preg_match( '/^1|2|3|4|5|6|7|8|9|a|b$/' , $this->req->get('kind') )){
			$this->req->setError('error9','価格タイプが不正です');
		}
		if( mb_strlen($this->req->get('order_point')) > 8 ){
			$this->req->setError('error91','発注点数量が長すぎます');
		}
		elseif($this->req->get('order_point') && !preg_match( '/^[0-9]+$/' , $this->req->get('order_point') )){
			$this->req->setError('error91','発注点数量が正しくありません');
		}

		$sql = "select * from m_goods";
		$sql .= " where customer_id='".$this->DB->getQStr($this->req->get('customer_id'))."'";
		$sql .= " and mark='".$this->DB->getQStr($this->req->get('mark'))."'";
		$sql .= " and product_number='".$this->DB->getQStr($this->req->get('product_number'))."'";
		$sql .= " and size='".$this->DB->getQStr($this->req->get('size'))."'";
//		$sql .= " and name='".$this->DB->getQStr($this->req->get('name'))."'";
		if($this->req->get('at')){
			$sql .= ' and autono != '.$this->DB->getQStr($this->req->get('at'));
		}
		$rs =& $this->DB->ASExecute($sql);
		if($rs){
			if(!$rs->EOF){
				$this->req->setError('error1','すでに同じ商品が登録されています');
			}
		}



/*		if($this->req->get('postcd')){
			if(!ereg("([0-9]{3})-([0-9]{4})",$this->req->get('postcd'))){
				$this->req->setError('error5','郵便番号の形式が間違っております');
			}
		}
		else{
			$this->req->setError('error6','郵便番号を入力してください');
		}
		if(!$this->req->get('prefecture')){
			$this->req->setError('error7','都道府県を選択してください');
		}
		if(!$this->req->get('address1')){
			$this->req->setError('error8','住所を入力してください');
		}
		if($this->req->get('tel')){
			if(!ereg("([0-9]{1,5})-([0-9]{1,5})-([0-9]{1,5})",$this->req->get('tel'))){
				$this->req->setError('error9','電話番号の形式が間違っております');
			}
		}
		else{
			$this->req->setError('error10','電話番号を入力してください');
		}
		if($this->req->get('fax')){
			if(!ereg("([0-9]{1,5})-([0-9]{1,5})-([0-9]{1,5})",$this->req->get('fax'))){
				$this->req->setError('error11','FAX番号の形式が間違っております');
			}
		}
		if(!$this->req->get('mail')){
			$this->req->setError('error12','メールアドレスを入力してください');
		}
		else{
			$regex = '/^[a-zA-Z0-9_\.\-]+?@[A-Za-z0-9_\.\-]+$/';
	        if (!preg_match($regex, $this->req->get('mail'))) {
				$this->req->setError('error13','メールアドレスの形式が間違っております');
	        }
		}

		if(!$this->req->get('member_id')){
			$this->req->setError('error14','会員IDを入力してください');
		}
		else{
			$sql = "select * from m_member";
			$sql .= " where member_id='".$this->DB->getQStr($this->req->get('member_id'))."'";
			$sql .= " and del_flg=0";
			if($this->req->get('at')){
				$sql .= " and autono!=".$this->DB->getQStr($this->req->get('at'));
			}
			$rs =& $this->DB->ASExecute($sql);
			if($rs){
				if(!$rs->EOF){
					$this->req->setError('error15','すでに同じ会員IDが登録されております');
				}
			}
		}
		if(!$this->req->get('dm_flg')){
			$this->req->setError('error16','DM配信の有無を入力してください');
		}
*/
		return true;
	}

	function data_comp(){

	}

	function check_data_make(){

		$util = new util();

		if($this->req->get('valid_flg') == 1){
			$this->templ->smarty->assign('valid_flg_value','有効');
		}
		else{
			$this->templ->smarty->assign('valid_flg_value','無効');
		}
		$this->templ->smarty->assign('goods_class_id_value',$util->goods_class_id_list($this->DB,1,$this->req->get('goods_class_id')));
		$this->templ->smarty->assign('kind_value',$util->kind_list(1,$this->req->get('kind')));
		$this->templ->smarty->assign('fourstar_value',$util->fourstar_list(1,$this->req->get('fourstar')));
		$this->templ->smarty->assign('zaiko_flg_value',$util->zaiko_flg_list(1,$this->req->get('zaiko_flg')));
		$this->templ->smarty->assign('nouhin_flg_value',$util->nouhin_flg_list(1,$this->req->get('nouhin_flg')));
		$this->templ->smarty->assign('headoffice3f_flg_value',$util->headoffice3f_list(1,$this->req->get('headoffice3f_flg')));

	}

	function data_get($at){

		$sql = "select * from m_goods";
		$sql .= " where autono=".$this->DB->getQStr($at);
		$rs =& $this->DB->ASExecute($sql);
//print_r($rs);
		if($rs){
			if(!$rs->EOF){
				$this->templ->smarty->assign('at',$rs->fields('autono'));
				$this->templ->smarty->assign('customer_id',$rs->fields('customer_id'));
				$this->templ->smarty->assign('mark',$rs->fields('mark'));
				$this->templ->smarty->assign('product_number',$rs->fields('product_number'));
				$this->templ->smarty->assign('product_number2',$rs->fields('product_number2'));
				$this->templ->smarty->assign('size',$rs->fields('size'));
				$this->templ->smarty->assign('name',$rs->fields('name'));
				$this->templ->smarty->assign('goods_class_id',$rs->fields('goods_class_id'));
				$this->templ->smarty->assign('cost',$rs->fields('cost'));
				$this->templ->smarty->assign('salesunitprice1',$rs->fields('salesunitprice1'));
				$this->templ->smarty->assign('salesunitprice2',$rs->fields('salesunitprice2'));
				$this->templ->smarty->assign('salesunitprice3',$rs->fields('salesunitprice3'));
				$this->templ->smarty->assign('salesunitprice4',$rs->fields('salesunitprice4'));
				$this->templ->smarty->assign('salesunitprice5',$rs->fields('salesunitprice5'));
				$this->templ->smarty->assign('salesunitprice6',$rs->fields('salesunitprice6'));
				$this->templ->smarty->assign('salesunitprice7',$rs->fields('salesunitprice7'));
				$this->templ->smarty->assign('salesunitprice8',$rs->fields('salesunitprice8'));
				$this->templ->smarty->assign('salesunitprice9',$rs->fields('salesunitprice9'));
				$this->templ->smarty->assign('salesunitprice10',$rs->fields('salesunitprice10'));
				$this->templ->smarty->assign('kind',$rs->fields('kind'));
				$this->templ->smarty->assign('new_product_number',$rs->fields('new_product_number'));

				$this->templ->smarty->assign('fourstar',$rs->fields('fourstar'));
				$this->templ->smarty->assign('disp_size',$rs->fields('disp_size'));
				$this->templ->smarty->assign('biko',$rs->fields('biko'));
				$this->templ->smarty->assign('valid_flg',$rs->fields('valid_flg'));
				$this->templ->smarty->assign('order_point',$rs->fields('order_point'));
				$this->templ->smarty->assign('zaiko_flg',$rs->fields('zaiko_flg'));
				if($rs->fields('zaiko_flg') == 1){
					$this->templ->smarty->assign('zaiko_flg',$rs->fields('zaiko_flg'));
				}
				else{
					$this->templ->smarty->assign('zaiko_flg',2);
				}
				$this->templ->smarty->assign('nouhin_flg',$rs->fields('nouhin_flg'));
				$this->templ->smarty->assign('haiban_date',$rs->fields('haiban_date'));
				$this->templ->smarty->assign('related_goods',$rs->fields('related_goods'));
//echo $rs->fields('zaiko_flg');

        		$sql = "select * from t_zaiko";
        		$sql .= " where mark='".$rs->fields('mark')."' and product_number='".$rs->fields('product_number')."' and size='".$rs->fields('size')."'";;
        		$rs =& $this->DB->ASExecute($sql);
        		if($rs){
        			if(!$rs->EOF){
        				$this->templ->smarty->assign('headoffice3f_flg',$rs->fields('headoffice3f_flg'));
                    }
                }
			}
		}
	}

	function form_make(){

		$UTL = new util();

		$this->templ->smarty->assign('goods_class_id_list',$UTL->goods_class_id_list($this->DB));
		$this->templ->smarty->assign('kind_list',$UTL->kind_list());
		$this->templ->smarty->assign('fourstar_list',$UTL->fourstar_list());
		$this->templ->smarty->assign('zaiko_flg_list',$UTL->zaiko_flg_list());
		$this->templ->smarty->assign('nouhin_flg_list',$UTL->nouhin_flg_list());
		$this->templ->smarty->assign('headoffice3f_list',$UTL->headoffice3f_list());

	}

}

?>