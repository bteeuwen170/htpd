$(document).ready(function()
{
	var converted = htmlDocx.asBlob(document.body.innerHTML);
	saveAs(converted, "portfolio.docx");
			//TODO Use name in doc. name
});
