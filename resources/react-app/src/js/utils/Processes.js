export const parts = [
  {label: 'バックドアインナー', value: 1},
  {label: 'アッパー', value: 2},
  {label: 'サイドアッパーRH', value: 3},
  {label: 'サイドアッパーLH', value: 4},
  {label: 'サイドロアRH', value: 5},
  {label: 'サイドロアLH', value: 6},
  {label: 'バックドアインナーASSY', value: 7}
];

export const processes = {
  1: [
    {label: '成形工程ライン１', value: 1},
    {label: '成形工程ライン２', value: 2, disabled: false},
    {label: '穴あけ工程', value: 3}
  ],
  2: [
    {label: '成形工程ライン１', value: 4},
    {label: '成形工程ライン２', value: 5, disabled: false},
    {label: '穴あけ工程', value: 6}
  ],
  3: [
    {label: '成形工程ライン１', value: 4},
    {label: '成形工程ライン２', value: 5, disabled: false},
    {label: '穴あけ工程', value: 6}
  ],
  4: [
    {label: '成形工程ライン１', value: 4},
    {label: '成形工程ライン２', value: 5, disabled: false},
    {label: '穴あけ工程', value: 6}
  ],
  5: [
    {label: '成形工程ライン１', value: 4},
    {label: '成形工程ライン２', value: 5, disabled: false},
    {label: '穴あけ工程', value: 6}
  ],
  6: [
    {label: '成形工程ライン１', value: 4},
    {label: '成形工程ライン２', value: 5, disabled: false},
    {label: '穴あけ工程', value: 6}
  ],
  7: [
    {label: '接着工程', value: 7}
  ]
};

export const inspections = {
  1: [
    {label: '外観検査', value: 1},
    {label: '精度検査', value: 3}
  ],
  2: [
    {label: '外観検査', value: 2, disabled: false},
    {label: '精度検査', value: 0, disabled: true}
  ],
  3: [
    {label: '外観検査', value: 15},
    {label: '穴検査', value: 4},
  ],
  4: [
    {label: '外観検査', value: 5},
    {label: '精度検査', value: 7, disabled: true}
  ],
  5: [
    {label: '外観検査', value: 6, disabled: false},
    {label: '精度検査', value: 0, disabled: true}
  ],
  6: [
    {label: '穴検査', value: 8},
  ],
  7: [
    {label: '精度検査', value: 9},
    {label: '簡易CF', value: 16},
    {label: '止水', value: 10},
    {label: '仕上', value: 11},
    {label: '検査', value: 12},
    {label: '手直し', value: 14}
  ]
};
