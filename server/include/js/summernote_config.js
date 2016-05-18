var button_save = function(context)
{
	var ui = $.summernote.ui;

	var button = ui.button({
		contents: "<span class='glyphicon glyphicon-floppy-save'> \
				</span>",
		tooltip: "Opslaan",
		click: function()
		{
			$(this).tooltip("hide");
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
			download(0);
		}
	});

	return button.render();
}

var button_download_students = function(context)
{
	var ui = $.summernote.ui;

	var button = ui.button({
		contents: "<span class='glyphicon glyphicon-download-alt'></span>",
		tooltip: "Downloaden",
		click: function()
		{
			$(this).tooltip("hide");
			download(1);
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
			$(this).tooltip("hide");
			save(0);
		}
	});

	return button.render();
}

function download(mode, loc)
{
	var data = loc || "editor";

	if (!loc) $("#" + data).summernote("destroy");

	var converted =
		htmlDocx.asBlob(document.getElementById(data).innerHTML);
	saveAs(converted, "document.docx"); //FIXME a better name (that's a pun)

	if (!loc && mode != -1)
		edit(mode);
}

function edit(mode, viewer)
{
	var viewer = viewer || false;

	var optionbar = document.getElementById("optionbar");
	if (optionbar != null)
		optionbar.style.display = "none";

	if (mode) {
		$("#editor").summernote({
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
			lang: "nl-NL",
			buttons: {
				save: button_save,
				download: button_download_students,
				finish: button_finish
			}
		});
	} else {
		$("#editor").summernote({
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
			lang: "nl-NL",
			buttons: {
				save: button_save,
				download: button_download
			}
		});

	}
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
