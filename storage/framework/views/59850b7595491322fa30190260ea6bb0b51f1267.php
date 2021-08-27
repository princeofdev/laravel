<div class="pl-lg-4">
    <form id="restorant-form" method="post" action="<?php echo e(route('admin.restaurants.update', $restorant)); ?>" autocomplete="off" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo method_field('put'); ?>
        <div class="row">
        <div class="col-md-6">
        <input type="hidden" id="rid" value="<?php echo e($restorant->id); ?>"/>
        <?php echo $__env->make('partials.fields',['fields'=>[
            ['ftype'=>'input','name'=>"Restaurant Name",'id'=>"name",'placeholder'=>"Restaurant Name",'required'=>true,'value'=>$restorant->name],
            ['ftype'=>'input','name'=>"Restaurant description",'id'=>"description",'placeholder'=>"Restaurant description",'required'=>true,'value'=>$restorant->description],
            ['ftype'=>'input','name'=>"Restaurant address",'id'=>"address",'placeholder'=>"Restaurant address",'required'=>true,'value'=>$restorant->address],
            ['ftype'=>'input','name'=>"Restaurant phone",'id'=>"phone",'placeholder'=>"Restaurant phone",'required'=>true,'value'=>$restorant->phone],
            ['ftype'=>'input','name'=>"Restaurant latitude",'id'=>"lat",'placeholder'=>"Restaurant latitude",'required'=>true,'value'=>$restorant->lat],
            ['ftype'=>'input','name'=>"Restaurant longitude",'id'=>"lng",'placeholder'=>"Restaurant longitude",'required'=>true,'value'=>$restorant->lng],
        ]], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <button type="button" id="get-latlong" class="btn btn-success mt-4"><?php echo e(__('Get Current Location')); ?></button>
        <button type="button" id="show-latlong" class="btn btn-success mt-4"><?php echo e(__('Show Current Location')); ?></button>
        <?php if(config('settings.multi_city')): ?>
            <?php echo $__env->make('partials.fields',['fields'=>[
                ['ftype'=>'select','name'=>"Restaurant city",'id'=>"city_id",'data'=>$cities,'required'=>true,'value'=>$restorant->city_id],
            ]], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>
       
        <?php if(auth()->user()->hasRole('admin')): ?>
            <br/>
            <div class="row">
                <div class="col-6 form-group<?php echo e($errors->has('fee') ? ' has-danger' : ''); ?>">
                    <label class="form-control-label" for="input-description"><?php echo e(__('Fee percent')); ?></label>
                    <input type="number" name="fee" id="input-fee" step="any" min="0" max="100" class="form-control form-control-alternative<?php echo e($errors->has('fee') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('fee', $restorant->fee)); ?>" required autofocus>
                    <?php if($errors->has('fee')): ?>
                        <span class="invalid-feedback" role="alert">
                            <strong><?php echo e($errors->first('fee')); ?></strong>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="col-6 form-group<?php echo e($errors->has('static_fee') ? ' has-danger' : ''); ?>">
                    <label class="form-control-label" for="input-description"><?php echo e(__('Static fee')); ?></label>
                    <input type="number" name="static_fee" id="input-fee" step="any" min="0" class="form-control form-control-alternative<?php echo e($errors->has('static_fee') ? ' is-invalid' : ''); ?>" value="<?php echo e(old('static_fee', $restorant->static_fee)); ?>" required autofocus>
                    <?php if($errors->has('static_fee')): ?>
                        <span class="invalid-feedback" role="alert">
                            <strong><?php echo e($errors->first('static_fee')); ?></strong>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
            <br/>
            <div class="form-group">
                <label class="form-control-label" for="item_price"><?php echo e(__('Is Featured')); ?></label>
                <label class="custom-toggle" style="float: right">
                    <input type="checkbox" name="is_featured" <?php if($restorant->is_featured == 1){echo "checked";}?>>
                    <span class="custom-toggle-slider rounded-circle"></span>
                </label>
            </div>
            <br/>
        <?php endif; ?>
        <br/>
        <?php if(config('app.isft')): ?>
            <?php echo $__env->make('partials.fields',['fields'=>[
                ['ftype'=>'bool','name'=>"Pickup",'id'=>"can_pickup",'value'=>$restorant->can_pickup == 1 ? "true" : "false"],
                ['ftype'=>'bool','name'=>"Delivery",'id'=>"can_deliver",'value'=>$restorant->can_deliver == 1 ? "true" : "false"],
                ['ftype'=>'bool','name'=>"Self Delivery",'id'=>"self_deliver",'value'=>$restorant->self_deliver == 1 ? "true" : "false"],
                ['ftype'=>'bool','name'=>"Free Delivery",'id'=>"free_deliver",'value'=>$restorant->free_deliver == 1 ? "true" : "false"],
            ]], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php elseif(config('app.isqrsaas') && !config('settings.is_whatsapp_ordering_mode')): ?>
            <?php echo $__env->make('partials.fields',['fields'=>[
                ['ftype'=>'bool','name'=>"Disable Call Waiter",'id'=>"disable_callwaiter",'value'=>$restorant->getConfig('disable_callwaiter', 0) ? "true" : "false"],
            ]], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>  
        <br/>
        <div class="row">
            <?php
                $images=[
                    ['name'=>'resto_wide_logo','label'=>__('Restaurant wide logo'),'value'=>$restorant->logowide,'style'=>'width: 200px; height: 62px;','help'=>"PNG 650x120 recomended"],
                    ['name'=>'resto_wide_logo_dark','label'=>__('Dark restaurant wide logo'),'value'=>$restorant->logowidedark,'style'=>'width: 200px; height: 62px;','help'=>"PNG 650x120 recomended"],
                    ['name'=>'resto_logo','label'=>__('Restaurant Image'),'value'=>$restorant->logom,'style'=>'width: 295px; height: 200px;','help'=>"JPEG 590 x 400 recomended"],
                    ['name'=>'resto_cover','label'=>__('Restaurant Cover Image'),'value'=>$restorant->coverm,'style'=>'width: 200px; height: 100px;','help'=>"JPEG 2000 x 1000 recomended"]
                ]
            ?>
            <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6">
                    <?php echo $__env->make('partials.images',$image, \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>    
        
        </div>
        <div class="col-md-6">
            <?php echo $__env->make('restorants.partials.ordering', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('restorants.partials.localisation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <!-- WHATS APP MODE -->
            <?php if(config('settings.is_whatsapp_ordering_mode')): ?>
                <?php echo $__env->make('restorants.partials.social_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
            <?php endif; ?>

            <?php if(config('settings.whatsapp_ordering')): ?>
                <!-- We have WP ordering -->
                <?php if(config('app.isft')): ?>
                    <!-- FT -->
                    <?php if(auth()->user()->hasRole('admin')): ?>
                        <?php echo $__env->make('restorants.partials.waphone', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- QR -->
                    <?php echo $__env->make('restorants.partials.waphone', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            <?php endif; ?>

        </div>

        </div>


        <div class="text-center">
            <!-- <button type="button" id="get-latlong" class="btn btn-success mt-4"><?php echo e(__('Get Current Location')); ?></button>
            <button type="button" id="show-latlong" class="btn btn-success mt-4"><?php echo e(__('Show Current Location')); ?></button> -->
            <button type="submit" class="btn btn-success mt-4"><?php echo e(__('Save')); ?></button>
        </div>
        
    </form>
    
</div>
<?php $__env->startSection('js'); ?>    
    <script>
        $('#get-latlong').on('click', function() {
            const input_latitude = document.querySelector('#lat');
            const input_longitude = document.querySelector('#lng');

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            } else { 
                x.innerHTML = "Geolocation is not supported by this browser.";
            }

            function showPosition(position) {
                input_latitude.value = position.coords.latitude;
                input_longitude.value = position.coords.longitude;
            }
        });
        $('#show-latlong').on('click', function() {
            var latitude = document.querySelector('#lat').value;
            var longitude = document.querySelector('#lng').value;

            if(latitude !== '' && longitude !== '') {
                // check their validation
                var pattern_lat = /^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/;
                var pattern_lng = /^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/;

                if (pattern_lat.test(latitude) && pattern_lng.test(longitude)) {
                    var url = `https://www.google.com/maps/place/${latitude},${longitude}`;
                    window.open(url, '_blank').focus();
                }
            }            
        });
    </script>
<?php $__env->stopSection(); ?>
<?php /**PATH /home/repairsp/eatin.pk/resources/views/restorants/partials/info.blade.php ENDPATH**/ ?>