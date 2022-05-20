@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                 <div class="ticket-wrapper">
                    <div class="card">
                    <div class="ticket-heading"><h6>Create Ticket</h6>
                        <a href="{{route('support.ticket.listing')}}" class="btn btn-sm btn-primary pull-right">Back</a>
                    </div>

                    <div class="panel-body">
                        <form class=" " role="form" method="POST"
                              action="{{route('support.ticket.store')}}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group col-md-6">
                                <label for="name" class="control-label">Name</label>
                                <input id="name" type="text" class="form-control" value="{{Auth::user()->name}}" disabled>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="email" class=" control-label">Email</label>

                                    <input id="email" type="text" class="form-control" value="{{Auth::user()->email}}" disabled>

                            </div>

                            <div class="form-group col-md-6">
                                <label for="title" class="control-label">Title</label>


                                    <input id="title" type="text" class="form-control" name="title"
                                           value="{{ old('title') }}" required>

                            </div>
                            <div class="form-group col-md-6">
                                <label for="category" class=" control-label">Category</label>

                                    <select id="category" type="category" class="form-control" name="category_id"
                                            required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $key=> $category)
                                            <option value="{{ $key }}" {{ !empty(old("category_id")) && old("category_id")==$key?"selected":'' }}>{{ $category }}</option>
                                        @endforeach
                                    </select>

                            </div>


                             <div class="form-group col-md-12">
                                <label for="message" class=" control-label">Message</label>

                                    <textarea required rows="10" id="message" class="form-control"
                                              name="message"></textarea>

                            </div>



                            <div class="form-group col-md-6">
                                <label for="message" class="control-label">Attachment (if any)</label>

                                    <input type="file" class="form-control" name="attachment" class="form-control" accept="image/png, image/gif, image/jpeg"/>

                            </div>

							   <div class="form-group col-md-6">
                                <label for="priority" class=" control-label">Priority</label>

                                    <select id="priority" type="" class="form-control" name="priority" required>
                                        <option value="">Select Priority</option>
                                        <option value="medium">Need to be fixed soon</option>
                                        <option value="low">Can Wait</option>
                                        <option value="high">Urgent</option>
                                    </select>

                            </div>

                            <div class="form-group">
                                <div class="col-md-12  text-center">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-ticket"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection

