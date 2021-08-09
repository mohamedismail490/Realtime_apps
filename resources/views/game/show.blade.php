@extends('layouts.app')

@push('styles')
    <style>
        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
        .refresh {
            animation: rotate 1.5s linear infinite;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Roulette Game') }}</div>
                    <div class="card-body">
                        <div class="text-center">
                            <img id="circle" src="{{ asset('img/circle.png') }}" class="" height="250" width="250">
                            <p id="winner" class="display-2 d-none text-primary"></p>
                        </div>
                        <hr>
                        <div class="text-center">
                            <label for="bet" class="font-weight-bold h5">Your Bet</label>
                            <select id="bet" class="custom-select col-auto">
                                <option selected>Not in</option>
                                @foreach(range(1,12) as $number)
                                    <option>{{$number}}</option>
                                @endforeach
                            </select>
                            <hr>
                            <p class="font-weight-bold h5">Remaining Time</p>
                            <p id="timer" class="h5 text-danger">Waiting to Start</p>
                            <hr>
                            <p id="result" class="h2"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const circleElement = document.getElementById('circle');
        const timerElement  = document.getElementById('timer');
        const winnerElement = document.getElementById('winner');
        const betElement    = document.getElementById('bet');
        const resultElement = document.getElementById('result');

        Echo.channel('game')
            .listen('RemainingTimeChanged', (e) => {
                winnerElement.classList.add('d-none');
                timerElement.innerText = e.time;
                circleElement.classList.add('refresh');
                resultElement.innerText = '';
                resultElement.classList.remove('text-success');
                resultElement.classList.remove('text-danger');
            })
            .listen('WinnerNumberGenerated', (e) => {
                circleElement.classList.remove('refresh');
                let winner = e.number;
                winnerElement.innerText = winner;
                winnerElement.classList.remove('d-none');

                let bet = betElement[betElement.selectedIndex].innerText;

                if (bet == winner) {
                    resultElement.innerText = "You WON";
                    resultElement.classList.add('text-success');
                }else {
                    resultElement.innerText = "You LOST!";
                    resultElement.classList.add('text-danger');
                }
            });
    </script>
@endpush