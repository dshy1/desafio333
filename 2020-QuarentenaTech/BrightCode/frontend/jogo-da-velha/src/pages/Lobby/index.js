import React from 'react';

import Room from './Room'

import './styles.css'

export default function Lobby() {
  return (
    <div className='lobby-container'>
      <div className='lobby'>
        <Room />
        <Room />
        <Room />
        <Room />
      </div>
      <div className='board-info'>
        <div className='board-user'>
          <div className='user-avatar'></div>
          <div className='user-name'>Guest #152111</div>
          <div className='game-score'>
            <ul>
              <li>Jogos</li>
              <li>Vitórias</li>
              <li>Derrotas</li>
              <li>Empates</li>
            </ul>
            <ul>
              <li>9</li>
              <li>5</li>
              <li>1</li>
              <li>3</li>
            </ul>
          </div>
          <div className='board-button'>
            <button className='buscar-jogo'>Buscar Jogo</button>
            <button className='criar-sala'>Criar sala</button>
          </div>
        </div>
      </div>
    </div>
  );
}
