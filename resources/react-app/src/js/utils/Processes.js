export const vehicles = [
  { label: '680A', value: '680A', disabled: false },
  { label: '950A', value: '950A', disabled: true }
];

export const parts = [
  { label: 'バックドアインナー', value: 1 },
  { label: 'アッパー', value: 2 },
  { label: 'サイドアッパーRH', value: 3 },
  { label: 'サイドアッパーLH', value: 4 },
  { label: 'サイドロアRH', value: 5 },
  { label: 'サイドロアLH', value: 6 },
  { label: 'バックドアインナーASSY', value: 7 }
];

export const processes = [
  { label: '成形工程ライン１', value: 'm001' },
  { label: '成形工程ライン２', value: 'm002' },
  { label: '穴あけ工程', value: 'h' },
  { label: '接着工程', value: 'j' }
];

export const inspections = [
  { label: '精度検査', value: 'inline' },
  { label: '外観検査', value: 'gaikan' },
  { label: '穴検査', value: 'ana' },
  { label: '簡易CF', value: 'kenicf' },
  { label: '止水', value: 'shisui' },
  { label: '仕上', value: 'shiage' },
  { label: '検査', value: 'kensa' },
  { label: '手直し', value: 'tenaoshi' }
];

export const inspectionGroups = [
  { vehicle: '680A', part: 1, p: 'm001', i: 'gaikan', iG: 1,  disabled: false },
  { vehicle: '680A', part: 1, p: 'm001', i: 'inline', iG: 3,  disabled: false },
  { vehicle: '680A', part: 1, p: 'm002', i: 'gaikan', iG: 2,  disabled: false },
  { vehicle: '680A', part: 1, p: 'm002', i: 'inline', iG: 0,  disabled: false },
  { vehicle: '680A', part: 1, p: 'h',    i: 'gaikan', iG: 15, disabled: false },
  { vehicle: '680A', part: 1, p: 'h',    i: 'ana',    iG: 4,  disabled: false },

  { vehicle: '680A', part: 2, p: 'm001', i: 'gaikan', iG: 5,  disabled: false },
  { vehicle: '680A', part: 2, p: 'm001', i: 'inline', iG: 7,  disabled: true },
  { vehicle: '680A', part: 2, p: 'm002', i: 'gaikan', iG: 6,  disabled: false },
  { vehicle: '680A', part: 2, p: 'm002', i: 'inline', iG: 0,  disabled: true },
  { vehicle: '680A', part: 2, p: 'h',    i: 'ana',    iG: 8,  disabled: false },

  { vehicle: '680A', part: 3, p: 'm001', i: 'gaikan', iG: 5,  disabled: false },
  { vehicle: '680A', part: 3, p: 'm001', i: 'inline', iG: 7,  disabled: true },
  { vehicle: '680A', part: 3, p: 'm002', i: 'gaikan', iG: 6,  disabled: false },
  { vehicle: '680A', part: 3, p: 'm002', i: 'inline', iG: 0,  disabled: true },
  { vehicle: '680A', part: 3, p: 'h',    i: 'ana',    iG: 8,  disabled: false },

  { vehicle: '680A', part: 4, p: 'm001', i: 'gaikan', iG: 5,  disabled: false },
  { vehicle: '680A', part: 4, p: 'm001', i: 'inline', iG: 7,  disabled: true },
  { vehicle: '680A', part: 4, p: 'm002', i: 'gaikan', iG: 6,  disabled: false },
  { vehicle: '680A', part: 4, p: 'm002', i: 'inline', iG: 0,  disabled: true },
  { vehicle: '680A', part: 4, p: 'h',    i: 'ana',    iG: 8,  disabled: false },

  { vehicle: '680A', part: 5, p: 'm001', i: 'gaikan', iG: 5,  disabled: false },
  { vehicle: '680A', part: 5, p: 'm001', i: 'inline', iG: 7,  disabled: true },
  { vehicle: '680A', part: 5, p: 'm002', i: 'gaikan', iG: 6,  disabled: false },
  { vehicle: '680A', part: 5, p: 'm002', i: 'inline', iG: 0,  disabled: true },
  { vehicle: '680A', part: 5, p: 'h',    i: 'ana',    iG: 8,  disabled: false },

  { vehicle: '680A', part: 6, p: 'm001', i: 'gaikan', iG: 5,  disabled: false },
  { vehicle: '680A', part: 6, p: 'm001', i: 'inline', iG: 7,  disabled: true },
  { vehicle: '680A', part: 6, p: 'm002', i: 'gaikan', iG: 6,  disabled: false },
  { vehicle: '680A', part: 6, p: 'm002', i: 'inline', iG: 0,  disabled: true },
  { vehicle: '680A', part: 6, p: 'h',    i: 'ana',    iG: 8,  disabled: false },

  { vehicle: '680A', part: 7, p: 'j',    i: 'inline', iG: 9,  disabled: false },
  { vehicle: '680A', part: 7, p: 'j',    i: 'kenicf', iG: 16, disabled: false },
  { vehicle: '680A', part: 7, p: 'j',    i: 'shisui', iG: 10, disabled: false },
  { vehicle: '680A', part: 7, p: 'j',    i: 'shiage', iG: 11, disabled: false },
  { vehicle: '680A', part: 7, p: 'j',    i: 'kensa',  iG: 12, disabled: false },
  { vehicle: '680A', part: 7, p: 'j',    i: 'tenaoshi', iG: 14, disabled: false }
]