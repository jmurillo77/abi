@foreach ($Operadoras as $Operadora)
    {{$Operadora->IdOperadora}} 
    {{$Operadora->Nombre}} 
    @foreach ($Operadora->numeros as $numero)
        {{$numero->Numero}}
    @endforeach
@endforeach