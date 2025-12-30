import BaseModel from '../core/BaseModel';

export default class Settings extends BaseModel {
    constructor() {
        super('settings-form', {
            slug: {
                sourceField: 'name',
                slugField: 'slug',
                separator: '_',
            }
        });
    }
}
