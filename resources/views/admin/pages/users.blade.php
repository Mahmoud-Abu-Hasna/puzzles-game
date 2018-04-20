@extends('admin.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 ">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <span>Users</span>
                    </div>

                    <div class="panel-body">

                        <div>
                            <form class="form-inline" action="{{ route('admin.users') }}">
                                <div class="form-group">
                                    <label for="winner">Winner:</label>
                                    <select id="winner"
                                            name="is_winner"
                                            class="form-control select2">
                                        <option value="1" {{ request('is_winner') == '1'?'selected':'' }} >Winner
                                        </option>
                                        <option value="0" {{ request('is_winner') == '0'?'selected':'' }}>Not Winner
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="has_complete">Completed:</label>
                                    <select id="has_complete"
                                            name="has_complete"
                                            class="form-control select2">
                                        <option value="1" {{ request('has_complete') == '1'?'selected':'' }} >Complete
                                        </option>
                                        <option value="0" {{ request('has_complete') == '0'?'selected':'' }}>Not Complete
                                        </option>
                                    </select>
                                </div>
                                <div class="checkbox">
                                    <label for="mac_address">Mac Address:</label>
                                    <input type="text" class="form-control" id="mac_address" name="mac_address" value="{{ request('mac_address') }}">
                                </div>
                                <button type="submit" class="btn btn-default">search</button>
                            </form>
                        </div>
<hr>
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>UserName</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>fcm_token</th>
                                <th>Is Winner?</th>
                                <th>Has Complete</th>
                                <th>Coins</th>
                                <th>Referrals</th>
                                <th>Referral Code</th>
                                <th>Current Enigma</th>
                                <th>Mac Address</th>
                                <th>Choices</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <th scope="row">{{ $user->id }}</th>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->address }}</td>
                                    <td>{{ $user->fcm_token }}</td>
                                    <td>{{ $user->is_winner }}</td>
                                    <td>{{ $user->has_complete }}</td>
                                    <td>{{ $user->coins }}</td>
                                    <td>{{ $user->referrals }}</td>
                                    <td>{{ $user->referral_code }}</td>
                                    <td>{{ $user->current_enigma }}</td>
                                    <td>{{ $user->mac_address }}</td>
                                    <td>
                                        <a href="/admin/user/{{ $user->id }}/winner" class="btn btn-{{ $user->is_winner?'success':'info' }} btn-xs" data-toggle="tooltip" data-placement="bottom" title="publish" ><i class="fa fa-star"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>

                                    <td colspan="13">No users are Provided</td>

                                </tr>

                            @endforelse
                            </tbody>
                        </table>
                        <div class="row text-center">
                            {{ $users->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
