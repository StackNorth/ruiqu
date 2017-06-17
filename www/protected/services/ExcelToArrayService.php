<?php
/**
 * Created by PhpStorm.
 * User: PHP
 * Date: 2016/11/28
 * Time: 16:20
 */
class ExcelToArrayService extends Service
{


    /* 导出excel函数*/
    public function push($data, $name = 'Excel') {
        error_reporting(E_ALL);
        date_default_timezone_set('Asia/Shanghai');
        $objPHPExcel = new PHPExcel();
        /*以下是一些设置 ，什么作者  标题啊之类的*/
        $objPHPExcel->getProperties()->setCreator("north")
            ->setLastModifiedBy("north")
            ->setTitle("数据EXCEL导出")
            ->setSubject("数据EXCEL导出")
            ->setDescription("备份数据")
            ->setKeywords("excel")
            ->setCategory("result file");
        /*以下就是对处理Excel里的数据， 横着取数据，主要是这一步，其他基本都不要改*/
        $num = 1;
        $objPHPExcel->setActiveSheetIndex(0)
            //Excel的第A列，uid是你查出数组的键值，下面以此类推
            ->setCellValue('A' . $num, 'ID')
            ->setCellValue('B' . $num, '订购的服务')
            //->setCellValue('C' . $num, '数量')
            //->setCellValue('D' . $num, '使用的代金券')
            ->setCellValue('C' . $num, '地址/联系方式')
            ->setCellValue('D' . $num, '支付方式')
            //->setCellValue('G' . $num, 'charge_id')
            ->setCellValue('E' . $num, '预约时间')
            //->setCellValue('F' . $num, '订单处理时间')
            ->setCellValue('F' . $num, '保洁师')
            ->setCellValue('G' . $num, '备注')
            ->setCellValue('H' . $num, '后台备注')
            ->setCellValue('I' . $num, '状态')
            ->setCellValue('J'.$num,'总额')
            ->setCellValue('K'.$num,'折后')
            ;
        $num += 1;
        foreach($data as $key => $value){
            $technicians = '';
            foreach($value['technicians'] as $k => $v){
                $technicians .= $v['technician_name'].'  ';
            }
            if (!empty($value['products'][0]['extra']['type']) && !empty($value['products'][0]['extra']['type'])){
                $product_str = $value['products_str'].$value['products'][0]['extra']['type'].$value['products'][0]['extra']['price'];
            } else {
                $product_str = $value['products_str'];
            }
            //var_dump();exit;.
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A' . $num, $value['id'])
                ->setCellValue('B' . $num, $product_str)
                //->setCellValue('C' . $num, $value['counts'])
               // ->setCellValue('D' . $num, $coupons)
                ->setCellValue('C' . $num, $value['address']['city'].$value['address']['area'].$value['address']['poi']['name']
                    .$value['address']['detail'].$value['address']['name'].'，手机号'.$value['address']['mobile'])
                ->setCellValue('D' . $num, $value['channel'])
               // ->setCellValue('G' . $num, $value['charge_id'])
                ->setCellValue('E' . $num, $value['booking_time_str'])
                //->setCellValue('F' . $num, $value['deal_time_str'])
                ->setCellValue('F' . $num, $technicians)
                ->setCellValue('G' . $num, $value['memo'])
                ->setCellValue('H' . $num, $value['remark'])
                ->setCellValue('I' . $num, $value['status_str'])
                ->setCellValue('J'.$num,$value['af_sum_price'])
                ->setCellValue('K'.$num,$value['sum_price'])
            ;
            $num += 1;
        }
       /* foreach ($data as $key => $value) {
            $ch1 = 65;
            $ch2 = 65;
            foreach ($value as $k => $v){

                if(is_array($v)){

                } else {
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    if ($ch1 > 90) {
                        $ch1 = 65;
                        $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ch1) . chr($ch2++).$num, $v);
                    } else{
                         $objPHPExcel->setActiveSheetIndex(0)->setCellValue(chr($ch1++) . $num, $v);
                    }


                }
            }
            $num += 1;
            }*/

        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $name . '.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}