<?php

########################################################################
# Extension Manager/Repository config file for ext: "exiftool"
#
# Auto generated 09-12-2008 10:11
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'exiftool',
	'description' => 'use exiftool to extract meta data',
	'category' => 'services',
	'author' => 'Martin Holtz',
	'author_email' => 'holtz@elemente.ms',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'experimental',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.1.2',
	'constraints' => array(
		'depends' => array(
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:116:{s:9:"ChangeLog";s:4:"506a";s:10:"README.txt";s:4:"aab4";s:35:"class.tx_exiftool_tcemainprocdm.php";s:4:"fd7f";s:21:"ext_conf_template.txt";s:4:"01c5";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"9465";s:14:"ext_tables.php";s:4:"1d86";s:14:"doc/manual.sxw";s:4:"9f69";s:19:"doc/wizard_form.dat";s:4:"c557";s:20:"doc/wizard_form.html";s:4:"e715";s:16:"exiftool/Changes";s:4:"3fe0";s:24:"exiftool/ExifTool_config";s:4:"6de7";s:17:"exiftool/MANIFEST";s:4:"b3da";s:17:"exiftool/META.yml";s:4:"04eb";s:20:"exiftool/Makefile.PL";s:4:"c6e3";s:15:"exiftool/README";s:4:"4263";s:17:"exiftool/exiftool";s:4:"d07d";s:21:"exiftool/exiftool.php";s:4:"9e19";s:22:"exiftool/iptc2xmp.args";s:4:"ae19";s:33:"exiftool/perl-Image-ExifTool.spec";s:4:"628c";s:22:"exiftool/xmp2iptc.args";s:4:"e3a6";s:33:"exiftool/lib/File/RandomAccess.pm";s:4:"83b3";s:34:"exiftool/lib/File/RandomAccess.pod";s:4:"c758";s:30:"exiftool/lib/Image/ExifTool.pm";s:4:"9ee3";s:31:"exiftool/lib/Image/ExifTool.pod";s:4:"62d8";s:35:"exiftool/lib/Image/ExifTool/AFCP.pm";s:4:"c3fb";s:35:"exiftool/lib/Image/ExifTool/AIFF.pm";s:4:"81a3";s:34:"exiftool/lib/Image/ExifTool/APE.pm";s:4:"2982";s:36:"exiftool/lib/Image/ExifTool/APP12.pm";s:4:"1f43";s:34:"exiftool/lib/Image/ExifTool/ASF.pm";s:4:"7a68";s:34:"exiftool/lib/Image/ExifTool/BMP.pm";s:4:"eeae";s:34:"exiftool/lib/Image/ExifTool/BZZ.pm";s:4:"5e2a";s:38:"exiftool/lib/Image/ExifTool/BigTIFF.pm";s:4:"3998";s:45:"exiftool/lib/Image/ExifTool/BuildTagLookup.pm";s:4:"7772";s:36:"exiftool/lib/Image/ExifTool/Canon.pm";s:4:"9f18";s:42:"exiftool/lib/Image/ExifTool/CanonCustom.pm";s:4:"056e";s:39:"exiftool/lib/Image/ExifTool/CanonRaw.pm";s:4:"f8cd";s:39:"exiftool/lib/Image/ExifTool/CanonVRD.pm";s:4:"9aee";s:36:"exiftool/lib/Image/ExifTool/Casio.pm";s:4:"8dbd";s:36:"exiftool/lib/Image/ExifTool/DICOM.pm";s:4:"a08e";s:34:"exiftool/lib/Image/ExifTool/DNG.pm";s:4:"7ff9";s:35:"exiftool/lib/Image/ExifTool/DjVu.pm";s:4:"e867";s:34:"exiftool/lib/Image/ExifTool/EXE.pm";s:4:"38d6";s:35:"exiftool/lib/Image/ExifTool/Exif.pm";s:4:"825c";s:35:"exiftool/lib/Image/ExifTool/FLAC.pm";s:4:"51ce";s:36:"exiftool/lib/Image/ExifTool/Fixup.pm";s:4:"20c6";s:36:"exiftool/lib/Image/ExifTool/Flash.pm";s:4:"c95f";s:39:"exiftool/lib/Image/ExifTool/FlashPix.pm";s:4:"fa98";s:42:"exiftool/lib/Image/ExifTool/FotoStation.pm";s:4:"2665";s:39:"exiftool/lib/Image/ExifTool/FujiFilm.pm";s:4:"7d06";s:34:"exiftool/lib/Image/ExifTool/GIF.pm";s:4:"b09e";s:34:"exiftool/lib/Image/ExifTool/GPS.pm";s:4:"2e20";s:38:"exiftool/lib/Image/ExifTool/GeoTiff.pm";s:4:"87b3";s:33:"exiftool/lib/Image/ExifTool/HP.pm";s:4:"932d";s:35:"exiftool/lib/Image/ExifTool/HTML.pm";s:4:"e9b7";s:39:"exiftool/lib/Image/ExifTool/HtmlDump.pm";s:4:"d4cc";s:42:"exiftool/lib/Image/ExifTool/ICC_Profile.pm";s:4:"266e";s:34:"exiftool/lib/Image/ExifTool/ID3.pm";s:4:"7acd";s:35:"exiftool/lib/Image/ExifTool/IPTC.pm";s:4:"45c6";s:34:"exiftool/lib/Image/ExifTool/ITC.pm";s:4:"bfef";s:35:"exiftool/lib/Image/ExifTool/JPEG.pm";s:4:"1e85";s:34:"exiftool/lib/Image/ExifTool/JVC.pm";s:4:"f2af";s:39:"exiftool/lib/Image/ExifTool/Jpeg2000.pm";s:4:"87e5";s:36:"exiftool/lib/Image/ExifTool/Kodak.pm";s:4:"832f";s:41:"exiftool/lib/Image/ExifTool/KyoceraRaw.pm";s:4:"1061";s:35:"exiftool/lib/Image/ExifTool/Leaf.pm";s:4:"1689";s:34:"exiftool/lib/Image/ExifTool/MIE.pm";s:4:"b525";s:40:"exiftool/lib/Image/ExifTool/MIEUnits.pod";s:4:"f117";s:35:"exiftool/lib/Image/ExifTool/MIFF.pm";s:4:"83f1";s:34:"exiftool/lib/Image/ExifTool/MNG.pm";s:4:"aedc";s:34:"exiftool/lib/Image/ExifTool/MPC.pm";s:4:"d3d8";s:35:"exiftool/lib/Image/ExifTool/MPEG.pm";s:4:"a86d";s:41:"exiftool/lib/Image/ExifTool/MakerNotes.pm";s:4:"33df";s:38:"exiftool/lib/Image/ExifTool/Minolta.pm";s:4:"2d5e";s:41:"exiftool/lib/Image/ExifTool/MinoltaRaw.pm";s:4:"0064";s:36:"exiftool/lib/Image/ExifTool/Nikon.pm";s:4:"3af2";s:43:"exiftool/lib/Image/ExifTool/NikonCapture.pm";s:4:"89ab";s:38:"exiftool/lib/Image/ExifTool/Olympus.pm";s:4:"234c";s:34:"exiftool/lib/Image/ExifTool/PDF.pm";s:4:"383e";s:35:"exiftool/lib/Image/ExifTool/PICT.pm";s:4:"592f";s:34:"exiftool/lib/Image/ExifTool/PNG.pm";s:4:"deee";s:34:"exiftool/lib/Image/ExifTool/PPM.pm";s:4:"a84b";s:40:"exiftool/lib/Image/ExifTool/Panasonic.pm";s:4:"4e82";s:37:"exiftool/lib/Image/ExifTool/Pentax.pm";s:4:"47ed";s:44:"exiftool/lib/Image/ExifTool/PhotoMechanic.pm";s:4:"5791";s:40:"exiftool/lib/Image/ExifTool/Photoshop.pm";s:4:"e88c";s:41:"exiftool/lib/Image/ExifTool/PostScript.pm";s:4:"5deb";s:38:"exiftool/lib/Image/ExifTool/PrintIM.pm";s:4:"db0a";s:40:"exiftool/lib/Image/ExifTool/QuickTime.pm";s:4:"5131";s:34:"exiftool/lib/Image/ExifTool/README";s:4:"a466";s:35:"exiftool/lib/Image/ExifTool/RIFF.pm";s:4:"3220";s:37:"exiftool/lib/Image/ExifTool/Rawzor.pm";s:4:"0aa9";s:35:"exiftool/lib/Image/ExifTool/Real.pm";s:4:"c98c";s:36:"exiftool/lib/Image/ExifTool/Ricoh.pm";s:4:"25cb";s:36:"exiftool/lib/Image/ExifTool/Sanyo.pm";s:4:"0a0a";s:36:"exiftool/lib/Image/ExifTool/Shift.pl";s:4:"089e";s:40:"exiftool/lib/Image/ExifTool/Shortcuts.pm";s:4:"5bdd";s:36:"exiftool/lib/Image/ExifTool/Sigma.pm";s:4:"a12d";s:39:"exiftool/lib/Image/ExifTool/SigmaRaw.pm";s:4:"100a";s:35:"exiftool/lib/Image/ExifTool/Sony.pm";s:4:"393a";s:40:"exiftool/lib/Image/ExifTool/TagLookup.pm";s:4:"961a";s:40:"exiftool/lib/Image/ExifTool/TagNames.pod";s:4:"938d";s:38:"exiftool/lib/Image/ExifTool/Unknown.pm";s:4:"3ec6";s:37:"exiftool/lib/Image/ExifTool/Vorbis.pm";s:4:"2977";s:44:"exiftool/lib/Image/ExifTool/WriteCanonRaw.pl";s:4:"5a07";s:40:"exiftool/lib/Image/ExifTool/WriteExif.pl";s:4:"1668";s:40:"exiftool/lib/Image/ExifTool/WriteIPTC.pl";s:4:"2f27";s:39:"exiftool/lib/Image/ExifTool/WritePDF.pl";s:4:"cc91";s:39:"exiftool/lib/Image/ExifTool/WritePNG.pl";s:4:"ea7c";s:45:"exiftool/lib/Image/ExifTool/WritePhotoshop.pl";s:4:"fa56";s:46:"exiftool/lib/Image/ExifTool/WritePostScript.pl";s:4:"41e8";s:39:"exiftool/lib/Image/ExifTool/WriteXMP.pl";s:4:"0fce";s:37:"exiftool/lib/Image/ExifTool/Writer.pl";s:4:"cfdb";s:34:"exiftool/lib/Image/ExifTool/XMP.pm";s:4:"1ee2";s:34:"exiftool/lib/Image/ExifTool/ZIP.pm";s:4:"dc4e";s:29:"sv1/class.tx_exiftool_sv1.php";s:4:"5236";}',
	'suggests' => array(
	),
);

?>