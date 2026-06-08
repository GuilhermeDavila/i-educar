<?php

use App\Services\ValidateUserPasswordService;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

uses(TestCase::class);

test('Aceita uma nova senha quando ela for válida no primeiro acesso', function () {
    $newPassword = 'Senha12*';
    $oldPassword = null;

    $hasherMock = mock(Hasher::class);
    $hasherMock->shouldReceive('check')
        ->once()
        ->with($newPassword, $oldPassword)
        ->andReturn(false);

    $service = new ValidateUserPasswordService($hasherMock);

    $service->execute($newPassword, $oldPassword);

    expect(true)->toBeTrue(); 
});


test('Aceita uma nova senha quando ela for válida e diferente da anterior', function () {
    $newPassword = 'Senha1234*';
    $oldPassword = 'Senha123*';

    $hasherMock = mock(Hasher::class);
    $hasherMock->shouldReceive('check')
        ->once()
        ->with($newPassword, $oldPassword)
        ->andReturn(false);

    $service = new ValidateUserPasswordService($hasherMock);

    $service->execute($newPassword, $oldPassword);

    expect(true)->toBeTrue();
});


test('Rejeita a nova senha se ela for igual à senha antiga', function () {
    $newPassword = 'Senha12*';
    $oldPassword = 'Senha12*';

    $hasherMock = mock(Hasher::class);
    $hasherMock->shouldReceive('check')
        ->once()
        ->with($newPassword, $oldPassword)
        ->andReturn(true);

    $service = new ValidateUserPasswordService($hasherMock);


    expect(fn () => $service->execute($newPassword, $oldPassword))
        ->toThrow(ValidationException::class, 'A senha informada foi usada recentemente. Por favor, escolha outra.');
});


test('Rejeita a nova senha se ela tiver menos de 8 caracteres', function () {
    $newPassword = 'Senh1*';
    $oldPassword = 'Outra12*';

    $hasherMock = mock(Hasher::class);
    $hasherMock->shouldReceive('check')
        ->once()
        ->with($newPassword, $oldPassword)
        ->andReturn(false);

    $service = new ValidateUserPasswordService($hasherMock);

    expect(fn () => $service->execute($newPassword, $oldPassword))
        ->toThrow(ValidationException::class, 'A senha deve conter pelo menos 8 caracteres e uma combinação de letras maiúsculas e minúsculas, números e símbolos (!@#$%*).');
});


test('Rejeita a nova senha se não houver nenhuma letra maiúscula', function () {
    $newPassword = 'senha12*';
    $oldPassword = 'Outra12*';

    $hasherMock = mock(Hasher::class);
    $hasherMock->shouldReceive('check')
        ->once()
        ->with($newPassword, $oldPassword)
        ->andReturn(false);

    $service = new ValidateUserPasswordService($hasherMock);

    expect(fn () => $service->execute($newPassword, $oldPassword))
        ->toThrow(ValidationException::class, 'A senha deve conter pelo menos 8 caracteres e uma combinação de letras maiúsculas e minúsculas, números e símbolos (!@#$%*).');
});


test('Rejeita a nova senha se não houver nenhuma letra minúscula', function () {
    $newPassword = 'SENHA12*';
    $oldPassword = 'Outra12*';

    $hasherMock = mock(Hasher::class);
    $hasherMock->shouldReceive('check')
        ->once()
        ->with($newPassword, $oldPassword)
        ->andReturn(false);

    $service = new ValidateUserPasswordService($hasherMock);


    expect(fn () => $service->execute($newPassword, $oldPassword))
        ->toThrow(ValidationException::class,'A senha deve conter pelo menos 8 caracteres e uma combinação de letras maiúsculas e minúsculas, números e símbolos (!@#$%*).');
});


test('Rejeita a nova senha se não houver nenhum número', function () {
    
    $newPassword = 'Senha***';
    $oldPassword = 'Outra12*';

    $hasherMock = mock(Hasher::class);
    $hasherMock->shouldReceive('check')
        ->once()
        ->with($newPassword, $oldPassword)
        ->andReturn(false);

    $service = new ValidateUserPasswordService($hasherMock);

   
    expect(fn () => $service->execute($newPassword, $oldPassword))
        ->toThrow(ValidationException::class,'A senha deve conter pelo menos 8 caracteres e uma combinação de letras maiúsculas e minúsculas, números e símbolos (!@#$%*).');
});


test('Rejeita a nova senha se não houver nenhum símbolo', function () {
    
    $newPassword = 'Senha123';
    $oldPassword = 'Outra12*';

    $hasherMock = mock(Hasher::class);
    $hasherMock->shouldReceive('check')
        ->once()
        ->with($newPassword, $oldPassword)
        ->andReturn(false);

    $service = new ValidateUserPasswordService($hasherMock);

    
    expect(fn () => $service->execute($newPassword, $oldPassword))
        ->toThrow(ValidationException::class,'A senha deve conter pelo menos 8 caracteres e uma combinação de letras maiúsculas e minúsculas, números e símbolos (!@#$%*).');
});