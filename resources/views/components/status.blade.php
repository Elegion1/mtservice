@if ($status == 'confirmed')
    <i class="bi bi-check-circle-fill text-success"></i>
@elseif ($status == 'pending')
    <i class="bi bi-exclamation-circle-fill text-warning"></i>
@elseif ($status == 'rejected')
    <i class="bi bi-x-circle-fill text-danger"></i>
@endif
