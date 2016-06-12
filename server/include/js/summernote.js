var m = -1;
var c = "";
var p = "";

var dirty = false;

function init(ms, cs, ps)
{
	m = ms;
	c = cs || "";
	p = ps || "";
}

function unload_msg(e)
{
	if (dirty)
		return "Weet u zeker dat u dit niet eerst wilt opslaan?";
	else
		return undefined;
}

window.top.onbeforeunload = unload_msg;
//window.contentwindow.onbeforeunload = unload_msg; //FIXME Doesn't work

function dirty_set()
{
	dirty = true; alert("asdf");
}

function name_set()
{
	var path = document.createElement("div");
	path.className = "path";
	path.innerHTML = p;

	var current = document.createElement("div");
	current.className = "current";
	current.innerHTML = c;

	path.appendChild(current);

	document.getElementsByClassName("note-toolbar")[0].appendChild(path);
}

var button_save = function(context)
{
	var ui = $.summernote.ui;

	var button = ui.button({
		contents: "<span class='glyphicon glyphicon-floppy-save'></span>",
		tooltip: "Opslaan",
		click: function()
		{
			$(this).tooltip("hide");
			dirty = false;
			save(1);
		}
	});

	return button.render();
}

var button_download = function(context)
{
	var ui = $.summernote.ui;

	var button = ui.button({
		contents: "<span class='glyphicon glyphicon-download-alt'></span>",
		tooltip: "Downloaden",
		click: function()
		{
			$(this).tooltip("hide");
			download();
		}
	});

	return button.render();
}

var button_finish = function(context)
{
	var ui = $.summernote.ui;

	var button = ui.button({
		contents: "<span class='glyphicon glyphicon-flag'></span>",
		tooltip: "Inleveren",
		click: function()
		{
			if (confirm("Weet u zeker dat u dit bestand wilt inleveren?")) {
				$(this).tooltip("hide");
				dirty = false;
				save(0);
				optionbar.style.display = "";
			}
		}
	});

	return button.render();
}

function download(l)
{
	var data = l || "editor";

	if (!l) $("#" + data).summernote("destroy");

	var converted =
		htmlDocx.asBlob(document.getElementById(data).innerHTML);
	saveAs(converted, "document.docx"); //FIXME a better name (that's a pun)

	if (!l && m != -1)
		edit();
}

function edit()
{
	var optionbar = document.getElementById("optionbar");
	if (optionbar != null)
		optionbar.style.display = "none";

	if (m) { //FIXME What a mess
		$("#editor").summernote({
			lang: "nl-NL",
			disableLinkTarget: true,
			fontNames: [
				"Arial", "Helvetica Neue", "Impact", "Lucida Grande", "Tahoma",
				"Times New Roman", "Verdana"
			],
			defaultFontName: "Arial",
			toolbar: [
				["save",	["save", "download", "finish"]],
				["tools",	["undo", "redo"]],
				["style",	["style", "clear"]],
				["font",	["fontname", "fontsize"]],
				["color",	["color"]],
				["text",	["bold", "italic", "underline",
									"strikethrough"]],
				["dimen",	["subscript", "superscript", "height"]],
				["align",	["ul", "ol", "paragraph"]],
				["table",	["table"]],
				["insert",	["link", "picture", "video"]]
			],
			buttons: {
				save: button_save,
				download: button_download,
				finish: button_finish
			},
			callbacks: {
				onChange: function(e) { dirty = true; }
			}
		});
	} else {
		$("#editor").summernote({
			lang: "nl-NL",
			disableLinkTarget: true,
			fontNames: [
				"Arial", "Helvetica Neue", "Impact", "Lucida Grande", "Tahoma",
				"Times New Roman", "Verdana"
			],
			defaultFontName: "Arial",
			toolbar: [
				["save",	["save", "download"]],
				["tools",	["undo", "redo"]],
				["style",	["style", "clear"]],
				["font",	["fontname", "fontsize"]],
				["color",	["color"]],
				["text",	["bold", "italic", "underline",
									"strikethrough"]],
				["dimen",	["subscript", "superscript", "height"]],
				["align",	["ul", "ol", "paragraph"]],
				["table",	["table"]],
				["insert",	["link", "picture", "video"]]
			],
			buttons: {
				save: button_save,
				download: button_download
			},
			callbacks: {
				onChange: function(e) { dirty = true; }
			}
		});
	}

	name_set();
}

(function($) {
	$.extend($.summernote.lang, {
		'nl-NL': {
			font: {
				bold: 'Vet',
				italic: 'Cursief',
				underline: 'Onderstrepen',
				clear: 'Stijl verwijderen',
				height: 'Regelafstand',
				name: 'Lettertype',
				strikethrough: 'Doorhalen',
				size: 'Tekstgrootte'
			},
			image: {
				image: 'Afbeelding',
				insert: 'Afbeelding invoegen',
				resizeFull: 'Volledige breedte',
				resizeHalf: 'Halve breedte',
				resizeQuarter: 'Kwart breedte',
				floatLeft: 'Links uitlijnen',
				floatRight: 'Rechts uitlijnen',
				floatNone: 'Geen uitlijning',
				dragImageHere: 'Sleep hier een afbeelding naar toe',
				selectFromFiles: 'Bestand',
				url: 'URL',
				remove: 'Verwijder afbeelding'
			},
			video: {
				video: 'Video',
				videoLink: 'Video link',
				insert: 'Video invoegen',
				url: 'URL',
				providers: ''
			},
			link: {
				link: 'Link',
				insert: 'Link invoegen',
				unlink: 'Link verwijderen',
				edit: 'Wijzigen',
				textToDisplay: 'Tekst',
				url: 'URL',
				openInNewWindow: 'Open in een nieuwe tab'
			},
			table: {
				table: 'Tabel'
			},
			hr: {
				insert: 'Horizontale lijn invoegen'
			},
			style: {
				style: 'Stijl',
				normal: 'Normaal',
				blockquote: 'Quote',
				pre: 'Code',
				h1: 'Kop 1',
				h2: 'Kop 2',
				h3: 'Kop 3',
				h4: 'Kop 4',
				h5: 'Kop 5',
				h6: 'Kop 6'
			},
			lists: {
				unordered: 'Ongenummerde lijst',
				ordered: 'Genummerde lijst'
			},
			paragraph: {
				paragraph: 'Uitlijning',
				outdent: 'Inspringen verkleinen',
				indent: 'Inspringen vergroten',
				left: 'Links uitlijnen',
				center: 'Centreren',
				right: 'Rechts uitlijnen',
				justify: 'Verdelen'
			},
			color: {
				recent: 'Kleuren',
				more: '',
				background: 'Achtergrond kleur',
				foreground: 'Tekstkleur',
				transparent: 'Automatisch',
				setTransparent: 'Automatisch',
				reset: 'Automatisch',
				resetToDefault: 'Automatisch'
			},
			shortcut: {
				shortcuts: 'Toetsencombinaties',
				close: 'sluiten',
				textFormatting: 'Tekststijlen',
				action: 'Acties',
				paragraphFormatting: 'Paragraafstijlen',
				documentStyle: 'Documentstijlen'
			},
			history: {
				undo: 'Ongedaan maken',
				redo: 'Opnieuw doorvoeren'
			}
		}
	});
})(jQuery);
