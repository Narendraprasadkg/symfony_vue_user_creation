export default class User {
    constructor(user) {
        this.id = user?.id ?? null;
        this.customerNumber = user?.customerNumber ?? null;
        this.anrede = user?.anrede ?? null;
        this.firstName = user?.firstName ?? null;
        this.secondName = user?.secondName ?? null;
        this.street = user?.street ?? null;
        this.houseNumber = user?.houseNumber ?? null;
        this.pin = user?.pin ?? null;
        this.location = user?.location ?? null;
        this.email = user?.email ?? null;
    }
}