<?php
require 'config_api.php';

/**
 * Función base para hacer peticiones a la API de Last.fm
 * 
 * @param string $metodo  El método de la API (artist.search, artist.getTopAlbums, etc.)
 * @param array  $params  Parámetros adicionales de la consulta
 * @return array|null     Los datos decodificados o null si hubo error
 */
function lastfm_peticion($metodo, $params = []) {
    global $lastfm_api_key, $lastfm_base_url;

    // Armamos los parámetros de la URL
    $params['method'] = $metodo;
    $params['api_key'] = $lastfm_api_key;
    $params['format'] = 'json';

    // Construimos la URL completa
    $url = $lastfm_base_url . '?' . http_build_query($params);

    // Hacemos la petición HTTP
    $respuesta = file_get_contents($url);

    // Si falló, retornamos null
    if ($respuesta === false) {
        return null;
    }

    // Convertimos el JSON a un arreglo de PHP
    return json_decode($respuesta, true);
}

/**
 * Buscar artistas por nombre
 */
function lastfm_buscar_artistas($nombre, $limite = 10) {
    $datos = lastfm_peticion('artist.search', [
        'artist' => $nombre,
        'limit' => $limite
    ]);

    if (!$datos || !isset($datos['results']['artistmatches']['artist'])) {
        return [];
    }

    return $datos['results']['artistmatches']['artist'];
}

/**
 * Obtener info detallada de un artista
 */
function lastfm_info_artista($nombre) {
    $datos = lastfm_peticion('artist.getinfo', [
        'artist' => $nombre
    ]);

    if (!$datos || !isset($datos['artist'])) {
        return null;
    }

    return $datos['artist'];
}

/**
 * Obtener los álbumes de un artista
 */
function lastfm_albumes_artista($nombre, $limite = 50) {
    $datos = lastfm_peticion('artist.getTopAlbums', [
        'artist' => $nombre,
        'limit' => $limite
    ]);

    if (!$datos || !isset($datos['topalbums']['album'])) {
        return [];
    }

    return $datos['topalbums']['album'];
}

/**
 * Obtener las canciones de un álbum
 */
function lastfm_canciones_album($artista, $album) {
    $datos = lastfm_peticion('album.getinfo', [
        'artist' => $artista,
        'album' => $album
    ]);

    if (!$datos || !isset($datos['album'])) {
        return null;
    }

    return $datos['album'];
}

/**
 * Buscar canciones por nombre
 */
function lastfm_buscar_canciones($cancion, $limite = 15) {
    $datos = lastfm_peticion('track.search', [
        'track' => $cancion,
        'limit' => $limite
    ]);

    if (!$datos || !isset($datos['results']['trackmatches']['track'])) {
        return [];
    }

    return $datos['results']['trackmatches']['track'];
}

/**
 * Obtener info detallada de una canción (incluyendo álbum y duración)
 */
function lastfm_info_cancion($artista, $cancion) {
    $datos = lastfm_peticion('track.getInfo', [
        'artist' => $artista,
        'track' => $cancion
    ]);

    if (!$datos || !isset($datos['track'])) {
        return null;
    }

    return $datos['track'];
}
?>