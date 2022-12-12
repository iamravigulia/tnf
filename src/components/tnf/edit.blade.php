<!--
  Tailwind UI components require Tailwind CSS v1.8 and the @tailwindcss/ui plugin.
  Read the documentation to get started: https://tailwindui.com/documentation
-->
<link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
@php $que = DB::table('fmt_tnf_ques')->where('id', $message)->first(); @endphp
<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="modalTnF{{$que->id}}">
    
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!--
        Background overlay, show/hide based on modal state.
  
        Entering: "ease-out duration-300"
          From: "opacity-0"
          To: "opacity-100"
        Leaving: "ease-in duration-200"
          From: "opacity-100"
          To: "opacity-0"
      -->
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
        <!--
        Modal panel, show/hide based on modal state.
  
        Entering: "ease-out duration-300"
          From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          To: "opacity-100 translate-y-0 sm:scale-100"
        Leaving: "ease-in duration-200"
          From: "opacity-100 translate-y-0 sm:scale-100"
          To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative -mx-8" role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <a onclick="closemodalTnF({{$message}})" class="rounded-full" style="background-color: red;color:white;font-size:40px;float: right;" href="javascript:void(0);">x</a>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <form action="{{route('fmt.tnf.update', $que->id)}}" method="post" enctype="multipart/form-data">
                    @if ($errors ?? '')
                        <div class="my-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                    @csrf
                    <div class="text-xl">Edit MCQpa Question {{$message}}</div>
                    <div class="flex flex-wrap -mx-4 my-2">{{-- flex-wrap --}}
                        <div class="w-full px-4">{{-- w-1/3 --}}
                            <div class="my-2">
                                <label class="bloc" for="">Format Title</label>
                                <input class="w-full border border-gray-500 rounded-lg p-2" name="format_title" id="" cols="30" rows="4" placeholder="Question" value="{{$que->format_title}}">
                            </div>
                        </div>{{-- //w-1/3 --}}
                        <div class="w-full px-4">{{-- w-1/3 --}}
                            <div class="my-2">
                                <label class="bloc" for="">Question</label>
                                <textarea class="w-full border border-gray-500 rounded-lg p-2" name="question" id="" cols="30" rows="4" placeholder="Question" required>{{$que->question}}</textarea>
                            </div>
                        </div>{{-- //w-1/3 --}}
                    </div>{{-- //flex-wrap --}}
                    <div class="flex flex-wrap -mx-4 my-4">{{-- flex-wrap --}}
                        <div class="w-full px-4">{{-- w-1/3 --}}
                            <div class="my-2">
                                <label class="bloc" for="">Hint</label>
                                <textarea class="w-full border border-gray-500 rounded-lg p-2" name="hint" id="" cols="30" rows="4" placeholder="Hint" maxlength="250">{{$que->hint ?? ''}}</textarea>
                            </div>
                        </div>{{-- //w-1/3 --}}
                    </div>
                    <div class="my-2">
                        <label class="bloc" for="">Difficulty Level</label>
                        @php $d_levels = DB::table('difficulty_levels')->get(); @endphp
                        <select name="difficulty_level_id" id="" class="w-full my-2 px-2 py-2 border border-gray-500 rounded-lg">
                            @if ($que->difficulty_level_id)
                                @php $mylevel = DB::table('difficulty_levels')->where('id', $que->difficulty_level_id)->first(); @endphp
                                    <option value="{{$mylevel->id}}">{{$mylevel->name}}</option>
                                @foreach ($d_levels as $level)
                                    @if ($level->id == $mylevel->id)
                    
                                    @else
                                        <option value="{{$level->id}}">{{$level->name}}</option>
                                    @endif
                                @endforeach
                            @else
                                @foreach ($d_levels as $level)
                                    <option value="{{$level->id}}">{{$level->name}}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    {{-- <div class="my-2">
                        <div class="flex flex-wrap -mx-2">
                            <div class="w-1/3 px-2">
                                <label class="w-full" for="">Level</label>
                                <select name="question_level" id="" class="w-full my-2 px-2 py-2 border border-gray-500 rounded-lg">
                                    <option value="{{$que->level_id}}" selected>{{$que->level_id}}</option>
                                    <option value="1">easy</option>
                                    <option value="2">medium</option>
                                    <option value="3">hard</option>
                                </select>
                            </div>
                            <div class="w-1/3 px-2">
                                <label class="w-full" for="">Score (optional)</label>
                                <input class="my-2 border border-gray-500 p-2 w-full rounded-lg" type="text" name="question_score" placeholder="Score" value="{{$que->score}}">
                            </div>
                            <div class="w-1/3 px-2">
                                <label class="w-full" for="">Hint (optional)</label>
                                <input class="my-2 border border-gray-500 p-2 w-full rounded-lg" type="text" name="question_hint" placeholder="hint" value="{{$que->hint}}">
                            </div>
                        </div>
                    </div> --}}
                    @php $answers = DB::table('fmt_tnf_ans')->where('question_id', $que->id)->pluck('id'); @endphp
                    @foreach ($answers as $ans)
                        @php $answer = DB::table('fmt_tnf_ans')->where('id', $ans)->first(); @endphp
                        {{--  --}}
                        <div class="flex flex-wrap my-4" id="ans1">
                            <div class="w-1/2">
                                <label class="w-full" for="">Answer</label>
                                <input type="text" value="{{$answer->id}}" name="ans{{$answer->id}}" hidden>
                                <input class="my-2 border border-gray-500 p-2 w-full rounded-lg" type="text" name="answer{{$answer->id}}" placeholder="Answer" value="{{$answer->answer}}">
                            </div>
                            <div class="w-1/2 px-4">
                                <label class="w-full" for="">Correct</label>
                                <input class="block my-3 w-10 h-10" type="checkbox" name="ans_correct{{$answer->id}}" id="" @if ($answer->arrange == 1) checked @endif>
                            </div>
                            <div class="w-1/2">
                                <label class="w-full" for="">English Word</label>
                                {{-- <input type="text" value="{{$answer->id}}" name="ans{{$answer->id}}" hidden> --}}
                                <input class="my-2 border border-gray-500 p-2 w-full rounded-lg" type="text" name="eng_word{{$answer->id}}" placeholder="English Word" value="{{$answer->eng_word}}">
                            </div>
                        </div>
                        {{--  --}}
                    @endforeach
                    {{-- <div class="w-full px-4">
                        <div class="my-2">
                            <label class="bloc" for="">Comment</label>
                            <textarea class="w-full border border-gray-500 rounded-lg p-2" name="hint" id="" cols="30" rows="4" placeholder="Comment">{{$que->hint}}</textarea>
                        </div>
                    </div> --}}
                    <button class="my-2 py-1 px-2 bg-blue-600 text-white rounded-lg" type="submit">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>