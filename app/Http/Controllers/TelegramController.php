<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\FileUpload\InputFile;

use Telegram\Bot\Laravel\Facades\Telegram;


class TelegramController extends Controller
{
    //
    public function updatedActivity()
    {
        $activity = collect();
        $info = Telegram::getUpdates();
        $botId = Telegram::getMe();
        //$firstName = Telegram::getFirstName();
        //$username = Telegram::getUsername();
        foreach ($info as $mensaje) {
            $activity->push($mensaje->message);
        }

        return view('chat', compact('activity', 'info', 'botId')); //'firstName', 'username'
    }

    public function sendMessage()
    {
        return view('telegramView');
    }
    public function storeMessage(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'message' => 'required'
        ]);

        $text =  "<b>Name: </b>\n"
            . "$request->name\n"
            . "<b>Message: </b>\n"
            . $request->message;

        Telegram::sendMessage([
            'chat_id' => '-1001765901670', //1397355961
            'parse_mode' => 'HTML',
            'text' => $text
        ]);

        return redirect()->back();
    }

    public function storePhoto(Request $request)
    {
        $request->validate([
            'file' => 'file|mimes:jpeg,png,gif'
        ]);

        $photo = $request->file('file');

        Telegram::sendPhoto([
            'chat_id' => '-1001765901670',
            'photo' => InputFile::createFromContents(file_get_contents($photo->getRealPath()), str_random(10) . '.' . $photo->getClientOriginalExtension()),
            'caption' => 'Photo Image'
        ]);

        return redirect()->back()->with('errors', 'No file selected');
    }
}
