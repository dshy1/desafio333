import React from 'react';

export default function Room() {
  return (
    <div className='room'>
      <div className='o-avatar'></div>
      <div className='vs-label'>vs</div>
      <div className='x-avatar'></div>
      <div className='o-symbol'></div>
      <div className='x-symbol'>
        <div className='x-line-one'></div>
        <div className='x-line-two'></div>
      </div>
      <div className='room-status-message'>Aguardando Jogador</div>
      <button className='room-button'>Entrar na sala</button>
    </div>
  );
}
