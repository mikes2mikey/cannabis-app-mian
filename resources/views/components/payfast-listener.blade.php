@push('payfast-event-listener')
<script>
    // Event listener for PayFast subscription updates
    const refreshComponent = () => {
        console.log('Refreshing subscription status')
        if (typeof Livewire !== 'undefined') {
            Livewire.emit('billingUpdated')
        }
    }
</script>
@endpush 