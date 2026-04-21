{{-- ─────────────────────────────────────────────────────────
     Partial rendered by VisaController::addTraveler()
     Returns one <x-traveler-card> fragment as HTML.

     Variables provided by the controller:
       $index        int
       $traveler     array   { name:'', uploads:{}, passport:null }
       $photoDoc     object|null
       $passportDocs Collection
       $otherDocs    Collection
─────────────────────────────────────────────────────────── --}}
<x-traveler-card
    :index="$index"
    :traveler="$traveler"
    :photoDoc="$photoDoc"
    :passportDocs="$passportDocs"
    :otherDocs="$otherDocs"
/>