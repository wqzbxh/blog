<?php
namespace app\admin\controller;
use think\Controller;
use IOFactory;
class LuntanVisitor extends Controller
{
		
	//初始化函数
	public function _initialize()
    {
      
    }
    /**
     * 导出访客表格
     */

	public function export()
    {
       Vendor("PHPExcel.IOFactory");
       vendor("phpexcel.PHPExcel");
       $excel = new \PHPExcel();
       $name = 'VisitorExcle';
       $luntanVisitorModel = new \app\admin\model\LuntanVisitor();
       $phpexcelModel = new \app\admin\model\PhpExcel();
       $excelField = $luntanVisitorModel->getTableFields();
       $visitorDataRows = $luntanVisitorModel->all();
       if(!empty($visitorDataRows))
       {
           foreach ($visitorDataRows as $visitorDataRow)
           {
               $info = array();
               $dataRow = $visitorDataRow->toArray();
               $info['id'] = $dataRow['id'];
               $info['host'] =  $dataRow['host'];
               $info['page'] =  $dataRow['page'];
               $info['browser'] =  $dataRow['browser'];
               $info['country'] =  $dataRow['country'];
               $info['city'] =  $dataRow['city'];
               $info['isp'] =  $dataRow['isp'];
               $info['create_at'] =  $dataRow['create_at'];
               $visitorData[] = $info;

           }
       }

       For($i=0;$i<count($excelField)-3;$i++)
       {
            $excel->getActiveSheet()->setCellValue($phpexcelModel::EXCELHEARD[$i].'1',"$excelField[$i]");
       }

        for ($i=2;$i<=count($visitorData)+1;$i++)
        {
            $j = 0;
            foreach ($visitorData[$i - 2]  as $key => $value)
            {
                $excel->getActiveSheet()->setCellValue($phpexcelModel::EXCELHEARD[$j]."$i","$value");
                $j++;
            }
        }
        $write = new \PHPExcel_Writer_Excel5($excel);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$name.'".xls"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');
        exit;
    }


    /**
     * 导入表格
     */
    public function  Reconstitute()
    {
        Vendor("PHPExcel.IOFactory");
        vendor("phpexcel.PHPExcel");
        $filename = $_FILES['userExcle']['tmp_name'];
        $reader = \PHPExcel_IOFactory::createReader('Excel2007'); // 读取 excel 文档

        $objPHPExcel = $reader->load($filename);
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow(); // 取得总行数D
        $highestColumn = $sheet->getHighestColumn();
        var_dump($highestColumn);
        for ($i = 2 ;$i<=$highestRow;$i++)
        {
            $info = array();
            $info['telephone'] = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();
            $info['nickname'] = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();
            $info['sex'] = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();
            $info['age'] = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();
            $userArray[] = $info;
        }

    }

}