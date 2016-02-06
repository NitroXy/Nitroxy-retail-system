# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure(2) do |config|
  config.vm.box = "debian/contrib-jessie64"
  config.vm.box_version = "8.3.0"

  config.vm.network "forwarded_port", guest: 80, host: 3010
  config.vm.synced_folder ".", "/vagrant"

  config.vm.provision "ansible_local" do |ansible|
    ansible.playbook = ".ansible/playbook.yml"
  end
end
