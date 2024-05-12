<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Services\SpotifyService;
use Symfony\Component\HttpFoundation\Response;

class AlbumController extends Controller
{
    private $spotifyService;

    public function __construct(SpotifyService $spotifyService)
    {
        $this->spotifyService = $spotifyService;
    }

    public function index(Request $request)
    {
        try {
            $bandName = $request->input('q');
            $accessToken = $this->spotifyService->getAccessToken();
            $albumsData = $this->spotifyService->searchAlbums($bandName, $accessToken);

            $albums = collect($albumsData)->map(function ($albumData) {
                return [
                    'name' => $albumData['name'],
                    'released' => $albumData['release_date'],
                    'tracks' => $albumData['total_tracks'],
                    'cover' => [
                        'height' => $albumData['images'][0]['height'],
                        'width' => $albumData['images'][0]['width'],
                        'url' => $albumData['images'][0]['url']
                    ]
                ];
            });

            return response()->json($albums);

        } catch (\Exception $e) {
            Log::error('Error al obtener los álbumes de Spotify: ' . $e->getMessage());
            
            return response()->json([
                'error' => 'Error al obtener los álbumes de Spotify. Por favor, inténtalo de nuevo más tarde.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        // Implementa la lógica para obtener un álbum por su ID
    }

    public function store(Request $request)
    {
        // Implementa la lógica para crear un nuevo álbum
    }

    public function update(Request $request, $id)
    {
        // Implementa la lógica para actualizar un álbum existente
    }

    public function destroy($id)
    {
        // Implementa la lógica para eliminar un álbum existente
    }
}

?>