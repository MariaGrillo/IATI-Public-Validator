import common
import os

def test_get_all_versions():
	"""
	Tests the expected output
	"""
	assert sorted(common.get_all_versions()) == ['1.01', '1.02', '1.03', '1.04', '1.05', '2.01', '2.02']


def test_get_all_versions_new_directory():
	# Add a new directory to 'iati-schemas'
	new_dir = "test_dir"
	os.makedirs(os.path.join('iati-schemas', new_dir))
	assert new_dir in common.get_all_versions()
	
	# Remove the directory after the test
	os.rmdir(os.path.join('iati-schemas', new_dir))
	assert new_dir not in common.get_all_versions() 
