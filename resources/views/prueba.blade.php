{{-- 
@foreach ($Numeros as $Numero)
    {{$Numero->IdTelefonoMovil}} 
    {{$Numero->Numero}} 
    {{$Numero->IdOperadora}} 
    {{$Numero->operadora->Nombre}}
    <br>
@endforeach --}}

{{-- 
@foreach ($Operadoras as $Operadora)
    {{$Operadora->IdOperadora}}
    {{$Operadora->Nombre}}
    @foreach ($Operadora->Numeros as $Num)
        {{$Num->IdTelefonoMovil}}
        {{$Num->Numero}}
    @endforeach
    <br>
@endforeach --}}

<h3>Persona <span>{{ $persona->Nombres }}</span> Tiene los Siguientes Numeros:</h3>
<table>
    <thead>
        <th>Numeros</th>
    </thead>
    <tbody>
        @foreach($persona->telefono_movils as $telefono)
        <tr>
            <td>
                {{$telefono->Numero}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<h3>Numero <span>{{ $numero->Numero }}</span> Es de las siguientes personas:</h3>
<table>
    <thead>
        <th>Personas</th>
    </thead>
    <tbody>
        @foreach($numero->personas as $persona)
        <tr>
            <td>
                {{$persona->Nombres}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>