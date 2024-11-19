@extends('layout.main')
@section('content')
    <form action="{{ route('mailing.store') }}" class="js-mailing-create__form" method="post">
        @csrf
        @if(old('phone'))
            @foreach(old('phone') as $phoneK => $phoneV)
                @include('mailing.partials.phone', [
                    'phoneK' => $phoneK,
                    'phoneV' => $phoneV,
                ])
            @endforeach
        @else
            @include('mailing.partials.phone', [
                'phoneK' => 0,
                'phoneV' => '',
            ])
        @endif
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" class="form-control js-mailing-create__message-textarea" id="message" rows="3">{{ old('message') }}</textarea>
            @error('message')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-light m-3 js-mailing-create__faker-btn">Faker</button>
    </form>
@endsection


