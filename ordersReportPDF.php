<?php
      //require 'allPages.php';
      require 'FPDF/fpdf.php';

      Class dbObj{
            /* Database connection start */
            var $dbhost = "localhost";
            var $username = "root";
            var $password = "";
            var $dbname = "singelcon";
            var $conn;
            function getConnstring() {
            $con = mysqli_connect($this->dbhost, $this->username, $this->password, $this->dbname) or die("Connection failed: " . mysqli_connect_error());
            
            /* check connection */
            if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
            } else {
            $this->conn = $con;
            }
            return $this->conn;
            }
            }

            class PDF extends FPDF
            {
            // Page header
            function Header()
            {
            $this->SetFont('Arial','u'.'b',20);
            $this->Cell(55);
            $this->Cell(80,10,'Orders List',0,0,'C');
            $this->Ln(20);
            }

            // Page footer
            function Footer()
            {
            // Position at 1.5 cm from bottom
            $this->SetY(-15);
            // Arial italic 8
            $this->SetFont('Arial','I',8);
            // Page number
            $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
            }
            }

            $db = new dbObj();
            $connString =  $db->getConnstring();
            $colums = array('orderNum','oDate','oUser');
            $top = mysqli_query($connString, "SELECT orderNum, oDate, oUser from orders GROUP by orderNum Order By oDate");

            $pdf = new PDF();
            //header
            $pdf->AddPage();
            //foter page
            $pdf->AliasNbPages();
            $pdf->SetFont('Arial','B',10);    
            foreach($top as $row) {
                $count = 0;
                foreach($colums as $colum){
                    if($colum=='orderNum'){
                        $pdf->Cell(65,12,"Order Number: ".$row[$colum],1,0,'C');
                        $orderNum = $row[$colum];
                        $count++;
                    }
                    else if($colum=='oDate'){
                        $pdf->Cell(65,12,"Order Date: ".$row[$colum],1,0,'C');
                        $count++;
                    }
                    else{
                        $pdf->Cell(65,12,"User Name: ".$row[$colum],1,0,'C');
                        $count++;
                    }
                    if($count==3){
                        $listOfBookds = mysqli_query($connString, "SELECT oBookName, oBookId from orders Where orderNum = $orderNum");
                        $pdf->Ln();
                        $pdf->SetFont('Arial','B',14);
                        $pdf->Cell(65,12,"The books that are on order:",);
                        $pdf->SetFont('Arial','',10);
                        foreach($listOfBookds as $books){
                            $pdf->Ln(7);
                            $pdf->Cell(65,12,"- Book Name: ".$books['oBookName'].".   Book Id: ".$books['oBookId']);
                        }
                        $pdf->Ln();
                        $pdf->Ln();
                        $pdf->SetFont('Arial','b',10);
                    }
                }
            }
            $pdf->Output('D','Orders Report.pdf');
            //$pdf->Output();
            ?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Simple Example of PDF file using PHP and MySQL</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
</head>
<body>
<div class="container" style="padding-top:50px">
<h2>Generate PDF file from MySQL Using PHP</h2>
<form class="form-inline" method="post" action="generate_pdf.php">
<button type="submit" id="pdf" name="generate_pdf" class="btn btn-primary"><i class="fa fa-pdf"" aria-hidden="true"></i>
Generate PDF</button>
</form>
</fieldset>
</div>
</body>
</html>