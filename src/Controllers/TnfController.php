<?php

namespace edgewizz\tnf\Controllers;

use App\Http\Controllers\Controller;
use Edgewizz\Edgecontent\Models\ProblemSetQues;
use Edgewizz\Tnf\Models\TnfAns;
use Edgewizz\Tnf\Models\TnfQues;
use Illuminate\Http\Request;

class TnfController extends Controller
{
    //
    public function test()
    {
        dd('hello');
    }
    public function store(Request $request)
    {
        $pmQ = new TnfQues();
        $pmQ->format_title = $request->format_title;
        $pmQ->hint = $request->hint;
        $pmQ->question = $request->question;
        $pmQ->difficulty_level_id = $request->difficulty_level_id;
        $pmQ->save();
        /* answer1 */
        if ($request->answer_1) {
            $answer_1 = new TnfAns();
            $answer_1->question_id = $pmQ->id;
            $answer_1->answer = $request->answer_1;
            if ($request->ans_correct_1) {
                $answer_1->arrange = 1;
            }
            $answer_1->eng_word = $request->eng_word1;
            $answer_1->save();
        }
        /* //answer1 */
        /* answer2 */
        if ($request->answer_2) {
            $answer_2 = new TnfAns();
            $answer_2->question_id = $pmQ->id;
            $answer_2->answer = $request->answer_2;
            if ($request->ans_correct_2) {
                $answer_2->arrange = 1;
            }
            $answer_2->eng_word = $request->eng_word2;
            $answer_2->save();
        }
        /* //answer2 */
        if($request->problem_set_id && $request->format_type_id){
            $pbq = new ProblemSetQues();
            $pbq->problem_set_id = $request->problem_set_id;
            $pbq->question_id = $pmQ->id;
            $pbq->format_type_id = $request->format_type_id;
            $pbq->save();
        }
        return back();
    }
    public function delete($id){
        $f = TnfQues::where('id', $id)->first();
        $f->delete();
        $ans = TnfAns::where('question_id', $f->id)->pluck('id');
        if($ans){
            foreach($ans as $a){
                $f_ans = TnfAns::where('id', $a)->first();
                $f_ans->delete();
            }
        }
        return back();
    }

    public function update($id, Request $request){
        $q = TnfQues::where('id', $id)->first();
        if($request->format_title){
            $q->format_title = $request->format_title;
        }
        $q->question = $request->question;
        $q->difficulty_level_id = $request->difficulty_level_id;
        // $q->level_id = $request->question_level;
        // $q->score = $request->question_score;
        $q->hint = $request->hint;
        $q->save();
        $answers = TnfAns::where('question_id', $q->id)->get();
        foreach($answers as $ans){
            if($request->ans.$ans->id){
                $inputAnswer = 'answer'.$ans->id;
                $inputArrange = 'ans_correct'.$ans->id;
                $inputEngWord = 'eng_word'.$ans->id;
                $ans->answer = $request->$inputAnswer;
                $ans->eng_word = $request->$inputEngWord;
                if($request->$inputArrange){
                    $ans->arrange = 1;
                }else{
                    $ans->arrange = 0;
                }
                $ans->save();
            }
        }
        return back();
    }

    public function csv(Request $request)
    {

        $file = $request->file('file');
        // dd($file);
        // File Details
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $tempPath = $file->getRealPath();
        $fileSize = $file->getSize();
        $mimeType = $file->getMimeType();
        // Valid File Extensions
        $valid_extension = array("csv");
        // 2MB in Bytes
        $maxFileSize = 2097152;
        // Check file extension
        if (in_array(strtolower($extension), $valid_extension)) {
            // Check file size
            if ($fileSize <= $maxFileSize) {
                // File upload location
                $location = 'uploads';
                // Upload file
                $file->move($location, $filename);
                // Import CSV to Database
                $filepath = public_path($location . "/" . $filename);
                // Reading file
                $file = fopen($filepath, "r");
                $importData_arr = array();
                $i = 0;
                while (($filedata = fgetcsv($file, 1000, ",")) !== false) {
                    $num = count($filedata);
                    // Skip first row (Remove below comment if you want to skip the first row)
                    if ($i == 0) {
                        $i++;
                        continue;
                    }
                    for ($c = 0; $c < $num; $c++) {
                        $importData_arr[$i][] = $filedata[$c];
                    }
                    $i++;
                }
                fclose($file);
                // Insert to MySQL database
                foreach ($importData_arr as $importData) {
                    $insertData = array(
                        "question"      => $importData[1],
                        "answer1"       => $importData[2],
                        "arrange1"      => $importData[3],
                        "eng_word1"     => $importData[4],
                        "answer2"       => $importData[5],
                        "arrange2"      => $importData[6],
                        "eng_word2"     => $importData[7],
                        "level"         => $importData[8],
                        "comment"       => $importData[9],
                    );
                    // var_dump($insertData['answer1']);
                    /*  */
                    if ($insertData['question']) {
                        $fill_Q = new TnfQues();
                        $fill_Q->question = $insertData['question'];
                        if($request->format_title){
                            $fill_Q->format_title = $request->format_title;
                        }
                        if ($insertData['comment']) {
                            $fill_Q->hint = $insertData['comment'];
                        }
                        if(!empty($insertData['level'])){
                            if($insertData['level'] == 'easy'){
                                $fill_Q->difficulty_level_id = 1;
                            }else if($insertData['level'] == 'medium'){
                                $fill_Q->difficulty_level_id = 2;
                            }else if($insertData['level'] == 'hard'){
                                $fill_Q->difficulty_level_id = 3;
                            }
                        }
                        $fill_Q->save();
                        if($request->problem_set_id && $request->format_type_id){
                            $pbq = new ProblemSetQues();
                            $pbq->problem_set_id = $request->problem_set_id;
                            $pbq->question_id = $fill_Q->id;
                            $pbq->format_type_id = $request->format_type_id;
                            $pbq->save();
                        }

                        if ($insertData['answer1'] == '-') {
                        } else {
                            $f_Ans1 = new TnfAns();
                            $f_Ans1->question_id = $fill_Q->id;
                            $f_Ans1->answer = $insertData['answer1'];
                            $f_Ans1->arrange = $insertData['arrange1'];
                            $f_Ans1->eng_word = $insertData['eng_word1'];
                            $f_Ans1->save();
                        }
                        if ($insertData['answer2'] == '-') {
                        } else {
                            $f_Ans2 = new TnfAns();
                            $f_Ans2->question_id = $fill_Q->id;
                            $f_Ans2->answer = $insertData['answer2'];
                            $f_Ans2->arrange = $insertData['arrange2'];
                            $f_Ans2->eng_word = $insertData['eng_word2'];
                            $f_Ans2->save();
                        }
                    }
                    /*  */
                }
                // Session::flash('message', 'Import Successful.');
            } else {
                // Session::flash('message', 'File too large. File must be less than 2MB.');
            }
        } else {
            // Session::flash('message', 'Invalid File Extension.');
        }
        return back();
    }
    public function active($id){
        $f = TnfQues::where('id', $id)->first();
        if($f->active == '0'){
            $f->active = '1';
            $f->save();
        }else{
            $f->active = '0';
            $f->save();
        }
        return back();
    }
}
