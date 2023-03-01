@extends('main')
@section('body')
<!-- Convert file -->
<div style="padding-top: 170px;" class="">
    <div class="containert">
        <div class="header-section">
            <div class="container overflow-hidden text-center">
                <div class="row gx-5">
                    <div class="col">
                        <div>PDF</div>
                    </div>
                    <div class="col">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>
                    <div class="col">
                        <div>PPT</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="drop-section">
            <div class="col">
                <div class="cloud-icon">
                    <img src="icons/cloud.png" alt="cloud">
                </div>
                <span>Drag & Drop your files here</span>
                <span>OR</span>
                <button style="background-color: #9385ff;" class="file-selector">Browse Files</button>
                <input type="file" class="file-selector-input" multiple>
            </div>
            <div class="col">
                <div class="drop-here">Drop Here</div>
            </div>
        </div>
        <div class="list-section">
            <div class="list-title">Uploaded Files</div>
            <div>Dinh</div>
            <div class="list"></div>
        </div>
    </div>
</div>
@endsection