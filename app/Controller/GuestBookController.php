<?php
namespace App\Controller;

use Hae\Core\HttpRequest;
use Hae\GuestBook;

class GuestBookController extends AbstractController
{
    private $gbRepository;

    protected function init()
    {
        $this->gbRepository = wapp()->create(GuestBook\GuestBookRepository::class);
    }

    public function list(HttpRequest $request) {
        $dataCollection = $this->gbRepository->list();
        return [
            'data' => array_map(function($e) { return $e->toArray(); }, $dataCollection)
        ];
    }

    public function create(HttpRequest $request) {
        $requestBodyAsArray = json_decode($request->getRawBody(), true);
        $guestBook = new GuestBook\GuestBook();
        $guestBook->assign($requestBodyAsArray);

        $this->gbRepository->store($guestBook);
        return $this->respondSuccess();
    }

    public function detail(HttpRequest $request, $id) {
        $guestBook = $this->gbRepository->findById(intval($id));
        return $guestBook->toArray();
    }

    public function edit(HttpRequest $request, $id) {
        if (!$this->isAuthorized()) {
            return $this->respondUnauthorized();
        }
        $requestBodyAsArray = json_decode($request->getRawBody(), true);  
        $guestBook = $this->gbRepository->findById(intval($id));
        $guestBook->setContent($requestBodyAsArray['content']);
        $this->gbRepository->update($guestBook);
        return $this->respondSuccess();
    }

    public function delete(HttpRequest $request, $id) {
        if (!$this->isAuthorized()) {
            return $this->respondUnauthorized();
        }
        $this->gbRepository->delete(intval($id));
        return $this->respondSuccess();
    }

}