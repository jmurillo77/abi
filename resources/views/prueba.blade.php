@foreach ($Numeros as $Numero)
    {{$Numero->IdTelefonoMovil}} 
    {{$Numero->Numero}} 
    {{$Numero->IdOperadora}} 
    {{$Numero->operadora->Nombre}}
    
   
    <br>
@endforeach