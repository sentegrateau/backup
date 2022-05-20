export const states: States[] = [
    {name: 'New South Wales', code: 'NSW'},
    {name: 'Queensland', code: 'QLD'},
    {name: 'South Australia', code: 'SA'},
    {name: 'Tasmania', code: 'TAS'},
    {name: 'Victoria', code: 'VIC'},
    {name: 'Western Australia', code: 'WA'},
    {name: 'Australian Capital Territory', code: 'ACT'},
    {name: 'Northern Territory', code: 'NT'}
];

export interface States {
    name: string;
    code: string;
}
