<?php
namespace app\admin\controller;
use think\Controller;
use IOFactory;
class Test extends Controller
{
		
	//初始化函数
	public function _initialize()
    {
      
    }
	
	/**
	*登录
	*
	*/ 
	public function index()
    {		
        var_dump($_FILES['file']);
        date_default_timezone_set("Asia/Shanghai");
        Vendor("PHPExcel.PHPExcel");
        Vendor("PHPExcel.PHPExcel.IOFactory");
        Vendor("PHPExcel.PHPExcel.Cell");
        $excel = new \PHPExcel();
        $objReader = $excel->createSheet($_FILES['file']['tmp_name']);
        var_dump($objReader);exit;
//         $objReader = \IOFactory::createReader($_FILES['file']['name']);
        //$objPHPExcelReader = $excel::createReader($_FILES['file']['name']);  //加载excel文件
//         var_dump($objPHPExcelReader);exit;
//         foreach($objPHPExcelReader->getWorksheetIterator() as $sheet)  //循环读取sheet
//         {
//             foreach($sheet->getRowIterator() as $row)  //逐行处理
//             {
//                 if($row->getRowIndex()<2)  //确定从哪一行开始读取
//                 {
//                     continue;
//                 }
//                 foreach($row->getCellIterator() as $cell)  //逐列读取
//                 {
//                     $data = $cell->getValue(); //获取cell中数据
//                     echo $data;
//                 }
//                 echo '<br/>';
//             }
//         }
    }	
    
    public function test()
    {
        		return $this->fetch('test/test');
    }
	public function kuayu()
    {		
        
        	 // Create a blank image.
            $image = imagecreatetruecolor(400, 300);
            
            // Select the background color.
            $bg = imagecolorallocate($image, 255, 102, 0);
            
            // Fill the background with the color selected above.
            imagefill($image, 0, 0, $bg);
            
            // Choose a color for the ellipse.
            $col_ellipse = imagecolorallocate($image, 255, 255, 255);

            $col_ellipse1 = imagecolorallocate($image, 0, 0, 255);
            // Draw the ellipse.
            imageellipse($image, 200, 150, 300, 200, $col_ellipse);
            
            // Output the image.
            header("Content-type: image/png");
            imagepng($image);
    }		
	public function index1()
    {		
            $a = imagecreatetruecolor(200, 200);
            $b = imagecolorallocate($a,102 , 255, 102);
            $col_ellipse1 = imagecolorallocate($a, 0, 0,255);
            $col_ellipse  = imagecolorallocate($a, 255, 255,255);
	        imagearc($a,100, 100, 100, 100, 0, 360, $b);
	        imagejpeg($a,'index.jpeg');
// 	        header("Content-type: image/png");
// 	        imagepng($a,'index.jpeg');
// 	        imagedestroy($a);
    }	
	
} 