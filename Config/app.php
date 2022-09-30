<?php

const APP_URL = 'http://getting-started.local/';
const SENDER_EMAIL_ADDRESS = 'alexander.naumov14@gmail.com';              
const PASSWORD = "kjvdghaqmmcgkfxw";
const RECAPTCHA_SITE_KEY = "6LfuiT0iAAAAACskGK0fwRD7f7lieqiPaj5QR38u";
const RECAPTCHA_SECRET_KEY = "6LfuiT0iAAAAAPxX60GxFl_ObuBRaGBQos25G9O4";
const IMAGE_FOLDER = "/images/";
const FILE_FOLDER = "/files/";
const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'png', 'jpeg'];
const ALLOWED_FILE_EXTENSIONS = ['pdf', 'docx'];
const ALLOWED_IMAGE_MIMETYPES = ['image/jpeg', 'image/png', 'image/jpg'];
const ALLOWED_FILE_MIMETYPES = ['application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
const ALLOWED_FILE_SIZE = 10;
enum FileTypes
{
    case Image;
    case File;
}