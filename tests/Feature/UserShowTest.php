<?php

it('has users endpoint', function () {
    $response = $this->get('/api/users');

    $response->assertStatus(200);
});
