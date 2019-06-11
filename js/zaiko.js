function date_format(element){

	var str1 = element.value;
	var str2;
	var str3;
	var str4;
	
	str1 = str1.replace("/","");
	str1 = str1.replace("/","");
	str1 = str1.replace("/","");
	str1 = str1.replace("/","");
	str1 = str1.replace("-","");
	str1 = str1.replace("-","");
	str1 = str1.replace("-","");
	str1 = str1.replace("-","");
	str1 = str1.replace(".","");
	str1 = str1.replace(".","");
	str1 = str1.replace(".","");
	str1 = str1.replace(".","");

	str2 = str1.substring(0,4);
	str3 = str1.substring(4,6);
	str4 = str1.substring(6,8);
	
	str1 = str2 + "/" + str3 + "/" + str4;
	
	element.value = str1;
}
