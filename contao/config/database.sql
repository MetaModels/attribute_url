-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the Contao    *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

--
-- Table `tl_metamodel_rendersetting`
--

CREATE TABLE `tl_metamodel_rendersetting` (
  `no_external_link` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_metamodel_attribute`
--

CREATE TABLE `tl_metamodel_attribute` (
  `trim_title` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
