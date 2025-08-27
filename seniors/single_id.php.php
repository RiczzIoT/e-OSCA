<?php
include '../includes/connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM senior WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_number = strtoupper($row['id_number']);
        $first_name = strtoupper($row['first_name']);
        $middle_name = strtoupper($row['middle_name']);
        $last_name = strtoupper($row['last_name']);
        $suffix = strtoupper($row['suffix']);
        $birthdate = date('m-d-Y', strtotime($row['birthdate'])); // Format birthdate as MM-DD-YYYY
        $province = strtoupper($row['province']);
        $age = strtoupper($row['age']);
        $sex = strtoupper(substr($row['sex'], 0, 1)); // Use only the first letter of the sex
        $barangay = strtoupper($row['barangay']);
        $municipal = strtoupper($row['municipal']);
        $municipal_with_comma = addslashes("$municipal,"); // Add comma and escape it
        $profile_picture = $row['profile_picture']; // Retrieve profile picture
        $qr_code = $row['qr_code'];
        $date_issued = date('m-d-Y'); // Set the current date as date issued

        // Combine the name parts
        $full_name = "$first_name $middle_name $last_name $suffix";

        // PDF Generation Code
        echo "<script src='../js/pdf-lib.min.js'></script>";
        echo "<script src='../js/fontkit.umd.min.js'></script>";
        echo "<script src='../js/FileSaver.js'></script>";
        echo "<script>
            const generatePDF = async () => {
                const { PDFDocument, rgb } = PDFLib;

                const existingPdfBytes = await fetch('OSCA_ID.pdf').then(res => res.arrayBuffer());

                const pdfDoc = await PDFDocument.load(existingPdfBytes);
                pdfDoc.registerFontkit(fontkit);

                const fontBytes = await fetch('./Times_New_Roman.ttf').then(res => res.arrayBuffer());

                const SanChezFont = await pdfDoc.embedFont(fontBytes);

                const pages = pdfDoc.getPages();
                const firstPage = pages[0];

                firstPage.drawText('$first_name', {
                    x: 51,
                    y: 94.5,
                    size: 6,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$middle_name', {
                    x: 51,
                    y: 86.8,
                    size: 6,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$last_name', {
                    x: 51,
                    y: 102.8,
                    size: 6,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$suffix', {
                    x: 79,
                    y: 102.8,
                    size: 6,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$birthdate', {
                    x: 17,
                    y: 41,
                    size: 7,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$id_number', {
                    x: 182,
                    y: 10,
                    size: 10,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$municipal_with_comma', {
                    x: 51,
                    y: 70.5,
                    size: 6,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$province', {
                    x: 85,
                    y: 70.5,
                    size: 6,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$barangay', {
                    x: 51,
                    y: 78.5,
                    size: 6,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$age', {
                    x: 94,
                    y: 41,
                    size: 7,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$sex', {
                    x: 144.2,
                    y: 41,
                    size: 7,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                firstPage.drawText('$date_issued', {
                    x: 194,
                    y: 41, // Adjust the y position as needed
                    size: 7,
                    font: SanChezFont,
                    color: rgb(0, 0, 0),
                });

                const qrImageBytes = await fetch('$qr_code').then(res => res.arrayBuffer());
                const qrImage = await pdfDoc.embedPng(qrImageBytes);
                firstPage.drawImage(qrImage, {
                    x: 171.3,
                    y: 59,
                    width: 62.6,
                    height: 62.6,
                });

                // Add the profile picture
                const profilePicBytes = await fetch('$profile_picture').then(res => res.arrayBuffer());
                const profilePic = await pdfDoc.embedJpg(profilePicBytes);
                firstPage.drawImage(profilePic, {
                    x: 135, // Adjust the x position
                    y: 74, // Adjust the y position
                    width: 31, // Adjust the width
                    height: 31, // Adjust the height
                });

                const pdfBytes = await pdfDoc.save();
                console.log('Done creating');

                // Use the senior's name for the PDF file name
                var file = new File([pdfBytes], '$full_name.pdf', {
                    type: 'application/pdf;charset=utf-8',
                });
                saveAs(file);

                // Redirect to the specified URL
                window.location.href = './?id=964587213670';
            };

            generatePDF();
        </script>";
    } else {
        echo "No record found";
    }

    $conn->close();
}
?>
