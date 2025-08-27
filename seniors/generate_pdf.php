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
        $profile_picture = '../' . ltrim($row['profile_picture'], './'); // Adjust path for profile picture
        $qr_code = '../' . ltrim($row['qr_code'], './'); // Adjust path for QR code
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

                firstPage.drawText('$suffix', { x: 79, y: 254.8, size: 6, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$last_name', { x: 51, y: 254.8, size: 6, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$first_name', { x: 51, y: 246.7, size: 6, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$middle_name', { x: 51, y: 238.8, size: 6, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$barangay', { x: 51, y: 230.7, size: 6, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$municipal_with_comma', { x: 51, y: 222.8, size: 6, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$province', { x: 85, y: 222.8, size: 6, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$birthdate', { x: 17, y: 194, size: 7, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$age', { x: 94, y: 194, size: 7, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$sex', { x: 144.2, y: 194, size: 7, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$date_issued', { x: 194, y: 194, size: 7, font: SanChezFont, color: rgb(0, 0, 0) });
                firstPage.drawText('$id_number', { x: 182, y: 162, size: 10, font: SanChezFont, color: rgb(0, 0, 0) });

                try {
                    const qrImageBytes = await fetch('$qr_code').then(res => res.arrayBuffer());
                    const qrImage = await pdfDoc.embedPng(qrImageBytes);
                    firstPage.drawImage(qrImage, { x: 171.2, y: 211.1, width: 62.6, height: 62.6 });
                } catch (error) {
                    console.error('Error embedding QR code:', error);
                }

                async function embedImage(pdfDoc, imageUrl) {
                    const response = await fetch(imageUrl);
                    const mimeType = response.headers.get('content-type');
                    const imageBytes = await response.arrayBuffer();

                    let embeddedImage;
                    if (mimeType === 'image/jpeg') {
                        embeddedImage = await pdfDoc.embedJpg(imageBytes);
                    } else if (mimeType === 'image/png') {
                        embeddedImage = await pdfDoc.embedPng(imageBytes);
                    } else {
                        throw new Error('Unsupported image type: ' + mimeType);
                    }

                    return embeddedImage;
                }

                try {
                    const profilePic = await embedImage(pdfDoc, '$profile_picture');
                    firstPage.drawImage(profilePic, { x: 134.9, y: 226, width: 32, height: 32 });
                } catch (error) {
                    console.error('Error embedding profile picture:', error);
                }

                const pdfBytes = await pdfDoc.save();
                var file = new File([pdfBytes], '$full_name.pdf', { type: 'application/pdf;charset=utf-8' });
                saveAs(file);
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
