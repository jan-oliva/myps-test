Vagrant.configure("2") do |config|
  config.vm.box = "collectora-debian-6.0.7"
#  config.vm.box_url = "https://dl.dropboxusercontent.com/u/3268793/package.box"
  config.vm.box_url = "https://www.dropbox.com/sh/csbujsadytad57t/5M20EOe94X/debian6/package.box"
  config.vm.synced_folder "./", "/vagrant", owner: "vagrant", group: "www-data", extra:"fmode=777,dmode=777"

  config.vm.define :myps do |server|
    server.vm.network :private_network, ip: "10.99.0.204"
    server.vm.provision :puppet do |puppet|
      puppet.manifest_file = "server.pp"
      puppet.manifests_path = "vagrant/manifests"
      puppet.module_path = "vagrant/modules"
    end
  end

  config.vm.provider :virtualbox do |v|
    # v.gui = true
    v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
    v.customize ["modifyvm", :id, "--memory", "750"]
  end
end