{{-- 
@foreach ($Numeros as $Numero)
    {{$Numero->IdTelefonoMovil}} 
    {{$Numero->Numero}} 
    {{$Numero->IdOperadora}} 
    {{$Numero->operadora->Nombre}}
    <br>
@endforeach --}}

@foreach ($Operadoras as $Operadora)
    {{$Operadora->IdOperadora}}
    {{$Operadora->Nombre}}
    @foreach ($Operadora->Numeros as $Num)
        {{$Num->IdTelefonoMovil}}
        {{$Num->Numero}}
    @endforeach
    <br>
@endforeach