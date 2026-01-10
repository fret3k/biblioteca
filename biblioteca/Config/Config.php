<?php
// Configuración para Azure (usa variables de entorno si están definidas, sino defaults de Azure)
const base_url = getenv('BASE_URL') ?: "https://biblioteca1-g3hkf3ebfqdfdqem.chilecentral-01.azurewebsites.net/";  // Cambia esto por la URL real de tu app en Azure
const host = getenv('DB_HOST') ?: "bd-biblioteca.mysql.database.azure.com";
const user = getenv('DB_USER') ?: "adminuser";
const pass = getenv('DB_PASS') ?: "199925@c";
const db = getenv('DB_NAME') ?: "biblioteca";
const charset = "charset=utf8";
?>