<?php


namespace Artemis\Core\Http\Interfaces;


interface ContentTypes
{
    /**
     * Content-Type for JSON
     */
    public const CONTENT_JSON = 'application/json';

    /**
     * Content-Type for non specified types like *.bin *.file *.com *.class *.ini
     */
    public const CONTENT_OCTET_STREAM = 'application/octet-stream';

    /**
     * Content-Type for *.pdf
     */
    public const CONTENT_PDF = 'application/pdf';

    /**
     * Content-Type for *.xml
     */
    public const CONTENT_XML_1 = 'application/xml';

    /**
     * Content-Type for *.xml
     */
    public const CONTENT_XML_2 = 'text/xml';

    /**
     * Content-Type for *.bmp
     */
    public const CONTENT_BMP = 'image/bmp';

    /**
     * Content-Type for *.bmp
     */
    public const CONTENT_BMP_X = 'image/x-bmp';

    /**
     * Content-Type for *.bmp
     */
    public const CONTENT_BMP_X_MS = 'image/x-ms-bmp';

    /**
     * Content-Type for *.gif
     */
    public const CONTENT_GIF = 'image/gif';

    /**
     * Content-Type for *.jpeg *.jpg *.jpe
     */
    public const CONTENT_JPG = 'image/jpeg';

    /**
     * Content-Type for *.png
     */
    public const CONTENT_PNG = 'image/png';

    /**
     * Content-Type for *.tiff
     */
    public const CONTENT_TIFF = 'image/tiff';

    /**
     * Content-Type for *.ico
     */
    public const CONTENT_ICO = 'image/x-icon';

    /**
     * Content-Type for *.css
     */
    public const CONTENT_CSS = 'text/css';

    /**
     * Content-Type for *.htm *.html *.shtml
     */
    public const CONTENT_HTML = 'text/html';

    /**
     * Content-Type for *.js
     */
    public const CONTENT_JS = 'text/javascript';

    /**
     * Content-Type for *.txt
     */
    public const CONTENT_TEXT = 'text/plain';

    /**
     * Content-Type for *.rtf
     */
    public const CONTENT_RTF = 'text/rtf';
}