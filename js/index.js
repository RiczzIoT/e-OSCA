const userName = document.getElementById("name");
const userAge = document.getElementById("age");
const requestorName = document.getElementById("requestor");
const submitBtn = document.getElementById("submitBtn");

const { PDFDocument, rgb } = PDFLib;

const capitalize = (str, lower = false) =>
  (lower ? str.toLowerCase() : str).replace(/(?:^|\s|["'([{])+\S/g, (match) =>
    match.toUpperCase()
  );

submitBtn.addEventListener("click", () => {
  const nameVal = capitalize(userName.value);
  const ageVal = userAge.value;
  const requestorVal = capitalize(requestorName.value);

  // check if the inputs are valid
  if (nameVal.trim() !== "" && userName.checkValidity() && userAge.checkValidity() && requestorName.checkValidity()) {
    generatePDF(nameVal, ageVal, requestorVal);
  } else {
    userName.reportValidity();
    userAge.reportValidity();
    requestorName.reportValidity();
  }
});

const generatePDF = async (name, age, requestor) => {
  const existingPdfBytes = await fetch("CERTIFICATE-OF-INDIGENCY.pdf").then((res) =>
    res.arrayBuffer()
  );

  // Load a PDFDocument from the existing PDF bytes
  const pdfDoc = await PDFDocument.load(existingPdfBytes);
  pdfDoc.registerFontkit(fontkit);

  //get font
  const fontBytes = await fetch("./Sanchez-Regular.ttf").then((res) =>
    res.arrayBuffer()
  );

  // Embed our custom font in the document
  const SanChezFont = await pdfDoc.embedFont(fontBytes);

  // Get the first page of the document
  const pages = pdfDoc.getPages();
  const firstPage = pages[0];

  // Draw text on the first page
  firstPage.drawText(name, {
    x: 210,
    y: 660,
    size: 12,
    font: SanChezFont,
    color: rgb(0, 0, 0),
  });

  firstPage.drawText(age, {
    x: 210,
    y: 640,
    size: 12,
    font: SanChezFont,
    color: rgb(0, 0, 0),
  });

  firstPage.drawText(requestor, {
    x: 210,
    y: 600,
    size: 12,
    font: SanChezFont,
    color: rgb(0, 0, 0),
  });

  const today = new Date();
  const day = today.getDate();
  const month = today.toLocaleString('default', { month: 'long' });
  const year = today.getFullYear();

  firstPage.drawText(`${day}`, {
    x: 120,
    y: 560,
    size: 12,
    font: SanChezFont,
    color: rgb(0, 0, 0),
  });

  firstPage.drawText(`${month}`, {
    x: 150,
    y: 560,
    size: 12,
    font: SanChezFont,
    color: rgb(0, 0, 0),
  });

  firstPage.drawText(`${year}`, {
    x: 230,
    y: 560,
    size: 12,
    font: SanChezFont,
    color: rgb(0, 0, 0),
  });

  // Serialize the PDFDocument to bytes (a Uint8Array)
  const pdfBytes = await pdfDoc.save();
  console.log("Done creating");

  var file = new File(
    [pdfBytes],
    "CERTIFICATE-OF-INDIGENCY.pdf",
    {
      type: "application/pdf;charset=utf-8",
    }
  );
  saveAs(file);
};
