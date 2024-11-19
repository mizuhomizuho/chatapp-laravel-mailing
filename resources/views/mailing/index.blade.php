@extends('layout.main')
@section('content')

    @if($list)
        {{ $list->withQueryString()->links() }}
        <table class="table table-dark js-mailing-index__main-box">
            <thead>
                <tr>
                    <th>#</th>
                    <th></th>
                    <th>#</th>
                    <th>#</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $listItem)
                    <tr class="js-mailing-index__list-item" data-status="{{ $listItem->status }}">
                        <td style="width: 0%">{{ $listItem->id }}</td>
                        <td style="width: 0%">
                            @if($listItem->status === \App\Models\Mailing::STATUS_NEW)
                            <div class="spinner-grow text-danger spinner-grow-sm" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            @endif
                        </td>
                        <td style="width: 0%" class="
                            @if($listItem->status === \App\Models\Mailing::STATUS_NEW)
                                text-info
                            @elseif($listItem->status === \App\Models\Mailing::STATUS_FAIL)
                                text-warning
                            @else
                                text-success
                            @endif
                        ">
                            {{ $listItem->status }}
                        </td>
                        <td style="width: 0%">{{ $listItem->phone }}</td>
                        <td>{{ substr($listItem->message, 0, 88) }} ...</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $list->withQueryString()->links() }}
    @else
        <div class="alert alert-light" role="alert">
            Empty...
        </div>
    @endif

@endsection


