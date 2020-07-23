<?php

namespace cards;

interface CardsRepository
{
    function findCards(): array;

    function findCard(int $id): ?Cards;

    function cardsCreate(Cards $cards): ? Cards;

    function update(Cards $cards): ?Cards;

    function delete(Cards $cards): ?Cards;
}