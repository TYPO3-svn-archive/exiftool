tx_exiftool_sv1 {
	exiftoolparams = -S -ALL
        # default output of exiftool
	fileCharset = utf-8
        dbCharset = iso-8859-1
	# For debug the devlog will be used,
	# $TYPO3_CONF_VARS['SYS']['enable_DLOG'] = 1;
	# has to be set for that!
	debug = 0
}

# http://de.wikipedia.org/wiki/IPTC-NAA-Standard
tx_exiftool_sv1.match {
   # field from dam = output from exiftool

	keywords.1 = keywords
	# TODO:
	# define special exiftool command-line tags are not possible atm
	keywords.tag.1 = -keywords
	# in this special case, we have a comma separated list in db
	# now we need to split that
	# and add for each keyword an parameter
	# results in: -keywords=value1 -keywords=value2 ...
        keywords.tag.1.splitToken = ,
	# this would add than the keywords to IPTC Data
	# keywords.tag.2 = -IPTC:keywords

	title.1 = Headline
        title.tag.1 = -headline

	# should we look up for category?
  # otherwise they will not be imported
	category.lookUpCategory = 1
	# the Token, how they are separated in meta-data
  category.lookUpCategory.splitToken = ,
  # should we create non existing categorys?
  category.lookUpCategory.insertNew = 1

	# comma separated list
	category.1 = Supplemental Categories
	# normaly only 3 characters?
	category.2 = Category
        category.tag.1 = -IPTC:SupplementalCategories
        category.tag.1.lookUpCategory = 1
        category.tag.ignoreUnchangedValue = 1

	file_orig_location.1 = Source
	file_orig_loc_desc
	# Program that created the file
	file_creator.1 = ProgramVersion
	file_creator.2 = Software
	file_creator.3 = Creator Tool
	ident.1 = Instance ID
	ident.2 = Document ID
	creator.1 = creator
	creator.2 = Artist
	creator.3 = by-line
        creator.tag.1 = creator
        creator.tag.2 = Artist
        creator.tag.3 = -IPTC:by-line

	description.1 = caption-abstract
	description.2 = image description
	description.3 = Description
        description.tag.1 = -IPTC:description
	alt_text
	caption
	abstract
	language
	publisher.1 = credit
        publisher.tag.1 = -IPTC:credit


	copyright.1 = rights
	copyright.2 = copyright
	copyright.3 = copyright notice
	copyright.tag = -IPTC:copyright

	instructions.1 = Instructions
	instructions.2 = Special Instructions
	loc_desc.1 = location
	loc_desc.2 = sub-location
	loc_desc.tag = -IPTC:location
	loc_country.1 = Country
	loc_country.2 = Country-Primary Location Name
        loc_country.tag = -IPTC:country

	loc_city.1 = city
	loc_city.tag.1 = -IPTC:city
	hres.1 = X Resolution
	vres.1 = Y Resolution

}